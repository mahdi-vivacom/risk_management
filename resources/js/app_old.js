import './bootstrap';

// Add ApexCharts
import '../theme/dist/libs/apexcharts/dist/apexcharts.min.js';

// Add JSVectorMap
import '../theme/dist/libs/jsvectormap/dist/js/jsvectormap.min.js';
import '../theme/dist/libs/jsvectormap/dist/maps/world.js';
import '../theme/dist/libs/jsvectormap/dist/maps/world-merc.js';

// Add Tabler Core
import '../theme/dist/js/tabler.min.js';
import '../theme/dist/js/demo.min.js';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
