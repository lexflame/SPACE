(function($){
  $.fn.mousePosScaled = function(e){
    const $el = $(this);

    // позиция элемента на странице
    const offset = $el.offset();

    // реальные размеры
    const realW = $el.outerWidth();
    const realH = $el.outerHeight();

    // вычисляем масштаб через getBoundingClientRect
    const rect = $el[0].getBoundingClientRect();
    const scaleX = rect.width / realW;
    const scaleY = rect.height / realH;

    // получаем позицию мыши с учётом scale
    const mouseX = parseFloat((e.pageX - offset.left) / scaleX) - 10;
    const mouseY = parseFloat((e.pageY - offset.top) / scaleY) - 10;

    $('#layers_of_marker').attr('data-mouseX',mouseX)
    $('#layers_of_marker').attr('data-mouseY',mouseY)
  };
})(jQuery);