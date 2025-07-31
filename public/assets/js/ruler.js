(function($) {
    $.fn.coordinateRuler = function() {
        const rulerContainer = this;
        const rulerHorizontal = $('<div class="ruler-horizontal"></div>');
        const rulerVertical = $('<div class="ruler-vertical"></div>');
        const pointer = $('<div class="pointer"></div>');

        rulerContainer.append(rulerHorizontal);
        rulerContainer.append(rulerVertical);
        rulerContainer.append(pointer);

        $(document).on('mousemove', function(event) {
            
        });

        return this;
    };

}(jQuery));     