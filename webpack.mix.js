let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
//Front-Theme styles and scripts
    .styles([
    ], 'public/css/public.css')

    .scripts([
    ], 'public/js/public.js')

    //Client-Dashboard theme styles and scripts
    .styles([
        'resources/assets/vendors/simplebar/simplebar.min.css',
        'resources/assets/phoenix/css/theme-rtl.min.css',
        'resources/assets/phoenix/css/theme.min.css',
        'resources/assets/phoenix/css/user-rtl.min.css',
        'resources/assets/phoenix/css/user.min.css',
        'resources/assets/vendors/leaflet/leaflet.css',
        'resources/assets/vendors/leaflet.markercluster/MarkerCluster.css',
        'resources/assets/vendors/leaflet.markercluster/MarkerCluster.Default.css',
        'resources/assets/vendors/toastr-js/toastr.css',
        'resources/assets/briowebapp/plugins/cropper/cropper.css',
        'resources/assets/custom/css/cropper-conf.css',
    ], 'public/css/dashboard.css')

    .scripts([
        'resources/assets/vendors/popper/popper.min.js',
        'resources/assets/vendors/bootstrap/bootstrap.min.js',
        'resources/assets/vendors/anchorjs/anchor.min.js',
        'resources/assets/vendors/is/is.min.js',
        'resources/assets/vendors/fontawesome/all.min.js',
        'resources/assets/vendors/lodash/lodash.min.js',
        'resources/assets/vendors/list.js/list.min.js',
        'resources/assets/vendors/feather-icons/feather.min.js',
        'resources/assets/vendors/dayjs/dayjs.min.js',
        'resources/assets/vendors/leaflet/leaflet.js',
        'resources/assets/vendors/leaflet.markercluster/leaflet.markercluster.js',
        'resources/assets/vendors/leaflet.tilelayer.colorfilter/leaflet-tilelayer-colorfilter.min.js',
        'resources/assets/vendors/echarts/echarts.min.js',
    ], 'public/js/dashboard.js')

    //Admin-theme styles and scripts
    .styles([
        //Basics
        'resources/assets/briowebapp/bootstrap/bootstrap.css',
        'resources/assets/briowebapp/fontawesome/font-awesome.css',
        'resources/assets/briowebapp/plugins/datatables/jquery.dataTables.css',
        'resources/assets/briowebapp/plugins/typeahead/typeahead.css',
        'resources/assets/briowebapp/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
        'resources/assets/briowebapp/plugins/bootstrap-chosen/chosen.css',
        'resources/assets/briowebapp/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.css',
        'resources/assets/briowebapp/plugins/switch-buttons/switch-buttons.css',
        'resources/assets/briowebapp/plugins/cropper/cropper.css',
        'resources/assets/briowebapp/app/app.v1.css',
        'resources/assets/vendors/toastr-js/toastr.css',
        'resources/assets/custom/css/cropper-conf.css',
        'resources/assets/custom/css/briowebapp-fixes.css',
    ], 'public/css/admin.css')

    .scripts([
        //Basics
        'resources/assets/briowebapp/jquery/jquery-1.9.1.min.js',
        //'resources/assets/js/briowebapp/plugins/underscore/underscore-min.js',
        'resources/assets/briowebapp/bootstrap/bootstrap.js',
        'resources/assets/briowebapp/globalize/globalize.min.js',
        'resources/assets/briowebapp/plugins/nicescroll/jquery.nicescroll.min.js',
        'resources/assets/briowebapp/app/custom.js',
    ], 'public/js/admin.js')

    .scripts([
        //App-Config
        'resources/assets/vendors/toastr-js/toastr.min.js',
        'resources/assets/custom/js/utils.js',
        'resources/assets/custom/js/app-config.js',
    ], 'public/js/app-config.js')

    .scripts([
        //Charts
        'resources/assets/briowebapp/plugins/DevExpressChartJS/dx.chartjs.js',
        'resources/assets/briowebapp/plugins/DevExpressChartJS/world.js',
        'resources/assets/briowebapp/plugins/DevExpressChartJS/demo-charts.js',
        'resources/assets/briowebapp/plugins/DevExpressChartJS/demo-vectorMap.js',
        'resources/assets/briowebapp/plugins/sparkline/jquery.sparkline.min.js',
        'resources/assets/briowebapp/plugins/sparkline/jquery.sparkline.demo.js',
        //'resources/assets/js/briowebapp/plugins/calendar/calendar.js',
        //'resources/assets/js/briowebapp/plugins/calendar/calendar-conf.js',
    ], 'public/js/admin-charts.js')

    .scripts([
        //DataTables
        'resources/assets/briowebapp/plugins/datatables/jquery.dataTables.js',
        'resources/assets/briowebapp/plugins/datatables/DT_bootstrap.js',
        'resources/assets/briowebapp/plugins/datatables/jquery.dataTables-conf.js',
    ], 'public/js/admin-datatables.js')

    .scripts([
        //Basic Forms
        'resources/assets/briowebapp/plugins/typehead/typeahead.bundle.js',
        'resources/assets/briowebapp/plugins/typehead/typeahead.bundle-conf.js',
        'resources/assets/briowebapp/plugins/inputmask/jquery.inputmask.bundle.js',
        'resources/assets/briowebapp/plugins/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'resources/assets/briowebapp/plugins/bootstrap-chosen/chosen.jquery.js',
        'resources/assets/briowebapp/moment/moment.js',
        'resources/assets/briowebapp/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
    ], 'public/js/admin-basic-forms.js')

    .scripts([
        //wysihtml
        'resources/assets/briowebapp/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js',
        'resources/assets/briowebapp/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.js',
    ], 'public/js/admin-wysihtml.js')

    .scripts([
        //Image Previewer & Cropper
        'resources/assets/briowebapp/plugins/cropper/cropper.min.js',
        'resources/assets/custom/js/preview.js',
    ], 'public/js/photo-cropper.js')
    .version();
