@php
    $name = $name ?? 'content';
    $label = $label ?? null;
    $value = $value ?? '';
    $dir = $dir ?? 'ltr';
    $rows = $rows ?? 8;
    $required = $required ?? false;
    $id = 'rte_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);
@endphp

<div>
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">
        {{ $label }}
        @if($required)<span class="text-red-500">*</span>@endif
    </label>
    @endif
    <textarea
        id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        dir="{{ $dir }}"
        data-rich-text-editor
        @if($required) required @endif
        class="border border-gray-300 rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
    >{{ $value }}</textarea>
</div>

@once
@push('styles')
<style>
    .tox-tinymce {
        border-radius: 0.5rem !important;
        border-color: #d1d5db !important;
    }
    .tox .tox-toolbar,
    .tox .tox-toolbar__overflow,
    .tox .tox-toolbar__primary {
        background: #f9fafb !important;
    }
    .tox .tox-statusbar {
        border-top-color: #e5e7eb !important;
    }
    .tox .tox-throbber {
        display: none !important;
    }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js"></script>
<script>
(function () {
  var tinymceBase = 'https://cdn.jsdelivr.net/npm/tinymce@7.6.0';

  function resizeAllEditors() {
    if (typeof tinymce === 'undefined') return;
    tinymce.editors.forEach(function (editor) {
      editor.fire('ResizeEditor');
    });
  }

  window.resizeRichTextEditors = resizeAllEditors;

  function isVisible(element) {
    return !!(element.offsetParent || element.getClientRects().length);
  }

  function initEditor(textarea) {
    if (textarea.getAttribute('data-tinymce-init') === '1') return;
    if (!isVisible(textarea)) return;

    textarea.setAttribute('data-tinymce-init', '1');
    var dir = textarea.getAttribute('dir') || 'ltr';

    tinymce.init({
      target: textarea,
      base_url: tinymceBase,
      suffix: '.min',
      license_key: 'gpl',
      skin_url: tinymceBase + '/skins/ui/oxide',
      content_css: false,
      height: 300,
      menubar: false,
      branding: false,
      promotion: false,
      resize: true,
      directionality: dir,
      plugins: 'lists link autolink code',
      toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | removeformat | code',
      block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4',
      content_style: 'body { font-family: ui-sans-serif, system-ui, sans-serif; font-size: 14px; line-height: 1.6; margin: 12px; color: #111827; }',
      setup: function (editor) {
        editor.on('change input undo redo', function () {
          editor.save();
        });
      },
      init_instance_callback: function (editor) {
        editor.fire('ResizeEditor');
      },
    });
  }

  window.initRichTextEditors = function () {
    if (typeof tinymce === 'undefined') return;
    document.querySelectorAll('[data-rich-text-editor]').forEach(initEditor);
  };

  function bootEditors() {
    window.initRichTextEditors();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootEditors);
  } else {
    bootEditors();
  }

  window.addEventListener('load', bootEditors);

  document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function () {
      if (typeof tinymce !== 'undefined') {
        tinymce.triggerSave();
      }
    });
  });
})();
</script>
@endpush
@endonce
