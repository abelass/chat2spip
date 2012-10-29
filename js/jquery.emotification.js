(function($){

  $.fn.emoticate = function(options){

    var defaults = ({
      replacediv:   'replaceme', 
      image_path:   '/images/emoticons/', 
      speed:        500, 
      icon:         'smiley'
    });
    var options = $.extend(defaults, options);

    return this.each(function() {
      var select = $('select', this);
      var area = $('textarea', this);
      var icon = $("#" + defaults.icon, this);

      var emo ='<div class="emoticon-box"><ul>';
      $('option', select).each(function () {
        var option    = $(this);
        var emocode        = option.val();
        var title    = option.text();
        var link = $.fn.emoticate.emoticlick(title, emocode, defaults.image_path);

        emo += '<li>'
            + link
            + '</li>';
      }); //end option
      emo += '</ul></div>';

      select.remove(); //get rid of the select to make way for emotification
      $("#" + defaults.replacediv, this).html(emo); //put the emotification HTML in the right div
      $("#" + defaults.replacediv, this).hide(); //hide the emoticons
      
      var listdiv = $("#" + defaults.replacediv, this); //reset the var so it will work in the icon click.. this probably could be done better

      //bind the link for every a tag that is in the replaced div
      $('a', this).bind('click', function() {
        $.fn.emoticate.insertme(area.attr('id'), $(this).attr('id'));
        return false;
      }); //end bind

      //click the icon
      $(icon, this).bind('click', function (){
        listdiv.show(defaults.speed);
      });

      //function to click anywhere to hide the emoticons
      $(document.body)click(function (){
        listdiv.hide(defaults.speed);
      });


    }); //end select

  }; //end function


  $.fn.emoticate.emoticlick = function(id,emocode, image_path){
    return '<a href="#" id="' + emocode +'"><img src="' + image_path + id + '"></a>';
  };

  $.fn.emoticate.insertme = function(areaId,text){
    var txtarea = document.getElementById(areaId);
    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
    "ff" : (document.selection ? "ie" : false ) );
      if (br == "ie") { 
      txtarea.focus(); var range = document.selection.createRange(); range.moveStart ('character', -txtarea.value.length);
      strPos = range.text.length;
    }
    else if (br == "ff") strPos = txtarea.selectionStart;

    var front = (txtarea.value).substring(0,strPos);  
    var back = (txtarea.value).substring(strPos,txtarea.value.length); 
    txtarea.value=front+text+back;
    strPos = strPos + text.length;
      if (br == "ie") { 
      txtarea.focus();
      var range = document.selection.createRange();
      range.moveStart ('character', -txtarea.value.length);
      range.moveStart ('character', strPos);
      range.moveEnd ('character', 0);
      range.select();
    }
    else if (br == "ff") {
      txtarea.selectionStart = strPos;
      txtarea.selectionEnd = strPos;
      txtarea.focus();
    }
    txtarea.scrollTop = scrollPos;
  };

})(jQuery);

