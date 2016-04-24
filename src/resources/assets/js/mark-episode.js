(function() {
    var token = $('meta[name="csrf-token"]').attr('content');

    function setEpisodeMark(watched, id){
        $.ajax({
            url: "/episode/" + id + '/watched?_token=' + token,
            cache: false,
            method: watched ? 'POST' : 'DELETE',
            json: true,
            success: function(res){
                //alert(res.status);
                $.notify(res.status, "success");
            }
        });
    }

    $('.mark-episode').each(function(){
        var $button = $(this);
        var initial = $(this).data('watchedInitial');
        var content = $(this).data('watchedContent').split('|');
        var classes = $(this).data('watchedClass').split('|');
        var episode = $(this).data('watchedEpisode');
        var season = $(this).data('watchedSeason');

        var state = initial ? 1 : 0;
        $button.html(content[state]);
        $button.removeClass('is-loading');
        $button.addClass(classes[state]);

        $button.click(function(){
            $button.removeClass(classes[state]);
            state = state === 0 ? 1 : 0;
            setEpisodeMark(state, episode);
            $button.html(content[state]);
            $button.addClass(classes[state]);
            return false;
        });
    });

    $('.mark-season').each(function(){
        var $button = $(this);
        var season = $(this).data('watchedSeason');

        $button.click(function(){
            var i = 0;
            $('.mark-episode[data-watched-season="' + season + '"]').each(function(){
                var $but = $(this);
                setTimeout(function(){
                    $but.click();
                }, 100*i);
                i++;
            });
        });
    });
}());
