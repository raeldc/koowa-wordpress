(function($){
    $(document).ready(function(){
        $('[data-toggle="checkbox"]').find('.btn').click(function(e){
            e.preventDefault();
            var $this     = $(this);
            var $parent   = $this.closest('[data-toggle="checkbox"]');
            var $input    = $parent.find('input');
            var $inputbtn = $input.closest('.btn');
            var selected  = false;

            // If this is not the button that hold the input button
            if (this !== $inputbtn[0]) {
                selected = $this.data('selected') !== 'false' && $this.data('selected') !== false && $this.hasClass('active');
                // Do nothing
                if (selected) {
                    return false;
                }
                // Select this button
                $this.addClass('active');
                $this.data('selected', true);
                // Unselect the others
                $input.prop('checked', false);
                $inputbtn.removeClass('active');
            }else{
                selected = $input.prop('checked') && $this.hasClass('active');
                // Do nothing
                if (selected) {
                    return false;
                }
                // Unselect the others
                $parent.find('.btn').removeClass('active');
                // Select this button
                $this.addClass('active');
                $input.prop('checked', true);
            }

            return false;
        });
    });
})(jQuery);