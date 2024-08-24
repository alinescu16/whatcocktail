<footer class="content-info">
  @php(dynamic_sidebar('sidebar-footer-start'))

  <section class="widget menu_widget widget_block">
    @if (has_nav_menu('footer_navigation'))
      <div class="nav-footer-wrapper">
        <h3>{!! __('Navigation', 'sage') !!}</h3>
        <nav class="nav-footer-navigation" aria-label="{{ wp_get_nav_menu_name('footer_navigation') }}">
          {!! wp_nav_menu(['theme_location' => 'footer_navigation', 'menu_class' => 'nav', 'echo' => false]) !!}
        </nav>
      </div>
    @endif

    @if (has_nav_menu('footer_legal'))
      <div class="nav-footer-wrapper">
        <h3>{!! __('Legal', 'sage') !!}</h3>
        <nav class="nav-footer-legal" aria-label="{{ wp_get_nav_menu_name('footer_legal') }}">
          {!! wp_nav_menu(['theme_location' => 'footer_legal', 'menu_class' => 'nav', 'echo' => false]) !!}
        </nav>
      </div>
    @endif
  </section>

  @php(dynamic_sidebar('sidebar-footer-end'))

  <section class="widget bellow_footer_widget widget_block">
    <p>Copyright © {{ now()->year }}</p>
  </section>

  <button id="scroll-to-top" class="scroll-to-top">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.6.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"/></svg>
  </button>
</footer>
