<?php

namespace App\Services;

use LDAP\Connection;

/**
 * Implementa un servicio LDAP
 */
final class LdapService
{
    private Connection|bool $connection;
    private static ?self $init = null;


    private function __construct(private ?string $user, private ?string $pass)
    {

        $this->user = !empty($user)
            ? "$user@" . env('LDAP_DOMAIN')
            : throw new \ErrorException("Usuario LDAP nulo");
        $this->pass = $pass ?? throw new \ErrorException("Contraseña LDAP nula");

        $this->connect();
    }

    public static function init(string $user = null, string $pass = null): static
    {
        if (is_null(self::$init)) {
            self::$init = new self($user, $pass);
        }
        return self::$init;
    }

    /**
     * Crea la conexion
     */
    private function connect(): void
    {
        $this->connection = ldap_connect(config('ldap.uri'));

        // Configurar opciones de conexión
        ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);

        if (!$this->connection) {
            throw new \RuntimeException("No se pudo conectar al servidor LDAP");
        }
    }


    /**
     * Autenticacion
     */
    public function auth(): bool
    {
        if ($this->connection) {
            try {
                return ldap_bind($this->connection, $this->user, $this->pass);
            } catch (\Throwable $th) {
                throw new \ErrorException("Error de autenticación LDAP: " . $th->getMessage());
            }
        }
    }

    /**
     * Busqueda
     */
    public function search(): array
    {
        $search_filter = '(&(objectClass=user)(sAMAccountName=*))';
        $search_attributes = ['cn', 'mail', 'sAMAccountName'];

        //Autenticacion para busqueda
        if ($this->auth()) {
            //Inicia la busqueda
            $search = ldap_search($this->connection, config('ldap.base_dn'), $search_filter, $search_attributes);
            if (!$search) {
                throw new \ErrorException("Error en la búsqueda LDAP:" . ldap_error($this->connection));
            }
            // Obtener los resultados de la búsqueda
            $results = ldap_get_entries($this->connection, $search);
            $this->close();
            if (!$results) {
                throw new \ErrorException("Error al obtener los resultados de busqueda en LDAP.");
            }
            return $results;
        }
        $this->close();
    }
    
    /**
     * Cerrar conexion
     */
    public function close() : void
    {
        ldap_unbind($this->connection);
        ldap_close($this->connection);
    }
}
