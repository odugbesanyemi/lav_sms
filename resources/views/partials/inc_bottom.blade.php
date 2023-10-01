
<!-- Theme JS files -->
<script src="{{ asset('global_assets/js/plugins/extensions/jquery_ui/interactions.min.js') }} "></script>
<script src="{{ asset('global_assets/js/plugins/forms/selects/select2.min.js') }} "></script>

{{--Forms--}}
<script src="{{ asset('global_assets/js/plugins/forms/wizards/steps.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/inputs/inputmask.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/forms/validation/validate.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/extensions/cookie.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/editors/trumbowyg/trumbowyg.min.js') }}"></script>
{{--Notify--}}
<script type="text/javascript" src="{{ asset('global_assets/js/plugins/notifications/sweet_alert2.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('global_assets/js/plugins/notifications/pnotify.min.js') }}"></script>

{{--DataTables--}}
<!-- <script src="{{ asset('global_assets/js/plugins/tables/datatables/datatables.min.js') }}"></script> -->
<!-- <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script> -->
<!-- <script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js') }}"></script>
<script src="{{ asset('global_assets/js/plugins/tables/datatables/extensions/buttons.min.js') }}"></script> -->

{{--Date Pickers--}}
<script src="{{ asset('global_assets/js/plugins/ui/moment/moment.min.js?v=1.0') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/bootstrap-datepicker.min.js?v=1.0') }}"></script>
<script src="{{ asset('global_assets/js/plugins/pickers/pickadate/legacy.js?v=1.0') }}"></script>

{{--Uploaders--}}
<script src="{{ asset('global_assets/js/plugins/uploaders/fileinput/fileinput.min.js?v=1.0') }}"></script>

{{--Calendar--}}
<script src="{{ asset('global_assets/js/plugins/ui/fullcalendar-6.1.8/dist/index.global.min.js?v=1.0') }}"></script>

<script src=" {{ asset('assets/js/app.js?v=1.0') }} "></script>
<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_wizard.js?v=1.0') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/form_select2.js?v=1.0') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/datatables_extension_buttons_html5.js?v=1.0') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/uploader_bootstrap.js?v=1.0') }}"></script>
<script src="{{ asset('global_assets/js/demo_pages/fullcalendar_basic.js?v=1.0') }}"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js?v=1.0"></script>
<!-- <script src="{{ asset('global_assets/js/plugins/ui/ripple.min.js') }}"></script> -->

<!-- /theme JS files -->
<script src=" {{ asset('assets/js/custom.js?v=1.0') }} "></script>
@include('partials.js.custom_js')
@include('partials.js.custom_datatable_js')
