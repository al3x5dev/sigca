import './bootstrap';
import Alpine from 'alpinejs';
import { DataTable } from 'simple-datatables';
import 'simple-datatables/dist/style.css';

// Hacemos que DataTable esté disponible globalmente para Alpine
window.DataTable = DataTable;

window.Alpine = Alpine;

Alpine.start();