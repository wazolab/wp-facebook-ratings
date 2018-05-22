jQuery(function($) {

  const plainDotSVG = '<svg class="icon icon-dot-plain d-block" aria-hidden="true" role="img"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-dot-plain"></use></svg>';
  const emptyDotSVG = '<svg class="icon icon-dot-empty d-block" aria-hidden="true" role="img"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-dot-empty"></use></svg>';

  $(document).ready(function() {
    $('#ratings-slider').on('slide.bs.carousel', function (e) {
      const fromElmt = $('.carousel-indicators li:eq('+e.from+')');
      const toElmt = $('.carousel-indicators li:eq('+e.to+')');
      fromElmt.find('svg').remove();
      fromElmt.append(emptyDotSVG);
      toElmt.find('svg').remove()
      toElmt.append(plainDotSVG);
		});
  });
});