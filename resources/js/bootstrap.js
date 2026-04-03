import axios from 'axios';
import './bootstrap';
import 'bootstrap';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
