
@if(isset($assets) && in_array('dashboard', $assets))
{{-- Chart Js --}}
<script src="{{ asset('js/charts/chart.js') }}"></script>
<script src="{{ asset('js/charts/apexcharts.min.js') }}"></script>
<script>
    window.routes = {
        charts: "{{ route('charts') }}"
    };
</script>
<script src="{{ asset('js/charts/apexcharts.js') }}"></script>
@endif

<!-- Backend Bundle JavaScript -->
<script src="{{ asset('js/backend-bundle.min.js') }}"></script>

<script src="{{ asset('js/raphael-min.js') }}"></script>

<script src="{{ asset('js/morris.js') }}"></script>
<script src="{{ asset('vendor/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('vendor/confirmJS/jquery-confirm.min.js') }}"></script>
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/aos.js') }}"></script>
<script>
   AOS.init();
   // Text Editor code
   if (typeof(tinyMCE) != "undefined") {
      // tinymceEditor()
      function tinymceEditor(target, button, height = 200) {
         var rtl = $("html[lang=ar]").attr('dir');
         tinymce.init({
            selector: target || '.textarea',
            directionality : rtl,
            height: height,
            plugins: [ 'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'help', 'wordcount' ],
            toolbar: 'undo redo | blocks | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
            automatic_uploads: false,
            /*file_picker_types: 'image',
            file_picker_callback: function(cb, value, meta) {
               var input = document.createElement('input');
               input.setAttribute('type', 'file');
               input.setAttribute('accept', 'image/*');

               input.onchange = function() {
                  var file = this.files[0];

                  var reader = new FileReader();
                  reader.onload = function() {
                     var id = 'blobid' + (new Date()).getTime();
                     var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                     var base64 = reader.result.split(',')[1];
                     var blobInfo = blobCache.create(id, file, base64);
                     blobCache.add(blobInfo);

                     cb(blobInfo.blobUri(), { title: file.name });
                  };
                  reader.readAsDataURL(file);
               };
               input.click();
            }*/
         });
      }
   }
   function showCheckLimitData(id){
      var checkbox =  $('#'+id).is(":checked")
      if(checkbox == true){
         $('.'+id).removeClass('d-none')
      }else{
         $('.'+id).addClass('d-none')

      }
   }
   function showMessage(message) {
      Snackbar.show({
         text: message,
         pos: 'bottom-center'
      });
   }
   function errorMessage(message) {
      Snackbar.show({
         text: message,
         pos: 'bottom-center',
         backgroundColor: '#dc3545',
         actionTextColor: 'white'
      });
   }
</script>
<script>
   function formValidation(formId, rules, messages) {
      $(formId).validate({
          rules: rules,
          messages: messages,
          errorClass: "help-block error",
          highlight: function(element) {
              $(element).closest(".form-group.row").addClass("has-error");
          },
          unhighlight: function(element) {
              $(element).closest(".form-group.row").removeClass("has-error");
          },
          errorPlacement: function(error, element) {
            if (element.hasClass('select2js')) {
                error.insertAfter(element.next('.select2-container'));
            } else {
                error.insertAfter(element);
            }
        }
      });
      $('.select2js').on('change', function() {
         $(this).valid();
     });
  }
</script>
<!-- dyanamic color -->

<script>
   $(document).ready(function() {

       if (!$('#no-results').length) {
         $('.mm-sidebar-menu').append(`
               <div id="no-results" style="display: none; text-align: center; padding: 1rem;">
                  <p style="color: #999; margin-top: 10px;">{{ __('message.no_menu_item_found')}}</p>
               </div>
         `);
      }

       $('.search-input').on('keyup', function () {
           var value = $(this).val().toLowerCase().trim();
           var hasVisibleItem = false;

           $('#mm-sidebar-toggle li').filter(function () {
               var itemText = $(this).text().toLowerCase();
               var match = itemText.indexOf(value) > -1;
               $(this).toggle(match);
           });

           $('#mm-sidebar-toggle li:has(h6)').each(function () {
               var nextItems = $(this).nextUntil('li:has(h6)');
               var hasVisible = nextItems.filter(':visible').length > 0;
               $(this).toggle(hasVisible);
               if (hasVisible) hasVisibleItem = true;
           });

           $('#no-results').toggle(!hasVisibleItem);

       });

       var root = document.documentElement;
       var siteColor = getComputedStyle(root).getPropertyValue('--site-color').trim();

       var hoverColor = lightenColor(siteColor, 20);
       root.style.setProperty('--site-hover-color', hoverColor);

       function lightenColor(hex, percent) {
         hex = hex.replace(/^#/, '');
         if (hex.length === 3) hex = hex.split('').map(c => c + c).join('');
         const r = parseInt(hex.slice(0,2),16), g = parseInt(hex.slice(2,4),16), b = parseInt(hex.slice(4,6),16);
         let [h, s, l] = rgbToHsl(r, g, b);
         l = Math.min(100, l + percent);
         const {r: r2, g: g2, b: b2} = hslToRgb(h, s, l);
         return `#${((1 << 24) + (r2 << 16) + (g2 << 8) + b2).toString(16).slice(1).toUpperCase()}`;
      }

      function rgbToHsl(r, g, b) {
         r /= 255; g /= 255; b /= 255;
         const max = Math.max(r, g, b), min = Math.min(r, g, b);
         let h = 0, s = 0, l = (max + min) / 2;
         if (max !== min) {
            const d = max - min;
            s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
            switch(max){
               case r: h = (g - b) / d + (g < b ? 6 : 0); break;
               case g: h = (b - r) / d + 2; break;
               case b: h = (r - g) / d + 4; break;
            }
            h *= 60;
         }
         return [h, s * 100, l * 100];
      }

      function hslToRgb(h, s, l) {
         s /= 100; l /= 100;
         const c = (1 - Math.abs(2 * l - 1)) * s;
         const x = c * (1 - Math.abs((h / 60) % 2 - 1));
         const m = l - c / 2;
         let r = 0, g = 0, b = 0;
         if (h < 60) [r, g, b] = [c, x, 0];
         else if (h < 120) [r, g, b] = [x, c, 0];
         else if (h < 180) [r, g, b] = [0, c, x];
         else if (h < 240) [r, g, b] = [0, x, c];
         else if (h < 300) [r, g, b] = [x, 0, c];
         else [r, g, b] = [c, 0, x];
         return {
            r: Math.round((r + m) * 255),
            g: Math.round((g + m) * 255),
            b: Math.round((b + m) * 255)
         };
      }
   });
</script>

@yield('bottom_script')

<!-- Masonary Gallery Javascript -->
<script src="{{ asset('js/masonry.pkgd.min.js') }}"></script>
<script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>

<!-- Chart Custom JavaScript -->
<script src="{{ asset('js/customizer.js') }}"></script>

<!-- Chart Custom JavaScript -->
<script src="{{ asset('js/chart-custom.js') }}"></script>

<!-- slider JavaScript -->
<script src="{{ asset('js/slider.js') }}"></script>

<!-- Emoji picker -->
<script type="module" src="{{ asset('vendor/emoji-picker-element/index.js') }}"></script>
<script type="module" src="{{ asset('vendor/magnific-popup/js/jquery.magnific-popup.min.js') }}"></script>

@if(isset($assets) && (in_array('datatable',$assets)))
<!-- <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script> -->
<!-- <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap4.min.js') }}"></script> -->
<!-- <script src="{{ asset('vendor/datatables/js/dataTables.buttons.min.js') }}"></script> -->
<!-- <script src="{{ asset('vendor/datatables/js/buttons.bootstrap4.min.js') }}"></script> -->
<script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
<!-- <script src="{{ asset('vendor/datatables/js/dataTables.select.min.js') }}"></script> -->
@endif

<!-- app JavaScript -->
@if(isset($assets) && in_array('phone', $assets))
    <script src="{{ asset('vendor/intlTelInput/js/intlTelInput-jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/intlTelInput/js/intlTelInput.min.js') }}"></script>
@endif

<script src="{{ asset('js/app.js') }}" defer></script>
<script src="{{asset('js/modelview.js')}}"></script>
@include('helper.app_message')
