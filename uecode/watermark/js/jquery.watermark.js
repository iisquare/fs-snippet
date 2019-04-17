(function ($) {

  function Watermark(dom, url, options) {
    var _this = this;
    this.$obj = $(dom);
    this.options = options;
    this.boxWidth = this.$obj.width();
    this.boxHeigth = this.$obj.height();
    this.background = new Image();
    this.$obj.html(this.options.loading);
    this.index = 0;
    this.background.onload = function () {
      _this.orgWidth = this.width;
      _this.orgHeigth = this.height;
      var adapt = _this.adapt(this.width, this.height, _this.boxWidth, _this.boxHeigth);
      _this.width = adapt.width;
      _this.height = adapt.height;
      _this.left = (_this.boxWidth - _this.width) / 2;
      _this.top = (_this.boxHeigth - _this.height) / 2;
      _this.$obj.css({
        'background-image': 'url(' + url + ')',
        'background-size': 'contain',
        'background-repeat': 'no-repeat',
        'background-position': 'center center'
      });
      _this.$obj.html('');
    };
    this.background.src = url;
    this.$select = null;

    this.result = function () {
      var result = {
        boxWidth: this.boxWidth,
        boxHeigth: this.boxHeigth,
        background: this.background.src,
        total: this.index,
        orgWidth: this.orgWidth,
        orgHeigth: this.orgHeigth,
        top: this.top,
        left: this.left,
        width: this.width,
        height: this.height,
        items: []
      };
      this.$obj.children('.' + this.options.itemClassName).each(function () {
        var $item = $(this);
        var item = {
          type: $item.data('type'),
          index: $item.data('index'),
          top: parseFloat($item.css('top').replace('px')),
          left: parseFloat($item.css('left').replace('px')),
          width: $item.width(),
          height: $item.height(),
          opacity: parseFloat($item.css('opacity')) * 100,
          content: {}
        };
        switch(item.type) {
          case 'image':
          item.content.url = $item.children('img').attr('src');
          break;
          case 'text':
          var $input = $item.children('input');
          item.content = $.extend({}, item.content, {
            'text': $input.val(),
            'font-family': $input.css('font-family'),
            'font-size': $input.css('font-size'),
            'font-style': $input.css('font-style'),
            'font-weight': $input.css('font-weight'),
            'color': $input.css('color')
          });
          break;
        }
        result.items.push(item);
      });
      return result;
    };

    this.draw = function (callback, result) {
      !result && (result = this.result());
      var canvas = document.createElement('canvas');
      var context = canvas.getContext("2d");
      var background = new Image();
      background.onload = function () {
        canvas.width = this.width;
        canvas.height = this.height;
        context.drawImage(this, 0, 0, this.width, this.height);
        for (var i in result.items) {
          var item = result.items[i];
          var rect = {
            x: (item.left - result.left) * result.orgWidth / result.width,
            y: (item.top - result.top) * result.orgHeigth / result.height,
            width: item.width * result.orgWidth / result.width,
            height: item.height * result.orgHeigth / result.height
          };
          switch (item.type) {
            case 'image':
            var image = new Image();
            image.src = item.content.url;
            var alpha = context.globalAlpha;
            context.globalAlpha = item.opacity / 100;
            context.drawImage(image, 0, 0, image.width, image.height, rect.x, rect.y, rect.width, rect.height);
            context.globalAlpha = alpha;
            break;
            case 'text':
            var fillStyle = context.fillStyle;
            var font = context.font;
            var alpha = context.globalAlpha;
            context.globalAlpha = item.opacity / 100;
            context.fillStyle = item.content.color;
            var fontSize = parseFloat(item.content['font-size'].replace('px')) * result.orgHeigth / result.height;
            context.font = [item.content['font-weight'], item.content['font-style'], parseInt(fontSize) + 'px', item.content['font-family']].join(' ');
            context.fillText(item.content.text, rect.x, rect.y + fontSize);
            context.fillStyle = fillStyle;
            context.font = font;
            context.globalAlpha = alpha;
            break;
          }
        }
        callback(canvas.toDataURL());
      }
      background.src = result.background;
    };

    this.backgroundUrl = function () {
      return this.background.src;
    };

    this.adapt = function (width, height, boxWidth, boxHeigth) {
      if(width > height) {
        if(width > boxWidth) {
          height = height * boxWidth / width;
          width = boxWidth;
        } else if (height > boxHeigth) {
          width = width * boxHeigth / height;
          height = boxHeigth;
        }
      } else {
        if (height > boxHeigth) {
          width = width * boxHeigth / height;
          height = boxHeigth;
        } else if(width > boxWidth) {
          height = height * boxWidth / width;
          width = boxWidth;
        }
      }
      return {width: width, height: height};
    };

    this.addItem = function (type, $item, resizeable) {
      var width = $item.width();
      var height = $item.height();
      var $div = $('<div></div>');
      var index = ++this.index;
      $item.css({
        position: 'absolute',
        left: '0px',
        top: '0px',
        width: '100%',
        height: '100%'
      });
      $div.css({
        position: 'absolute',
        border: '1px hidden',
        'z-index': index,
        width: width + 'px',
        height: height + 'px',
        top: ((_this.width - height) / 2 + _this.top),
        left: ((_this.height - width) / 2 + _this.left)
      });
      switch(type) {
        case 'text':
          $item.css({
            'line-height': 'normal',
            background: 'none',
            border: 'none',
            outline: 'none'
          });
        break;
        case 'image':
          $item.css({
            'vertical-align': 'inherit'
          });
          $div.css({
            'line-height': '100%'
          });
        break;
      }
      $div.html($item);
      $div.addClass(this.options.itemClassName);
      $div.data({
        'index': index,
        'type': type
      });
      if(resizeable) {
        var $resize = $('<i class="' + this.options.resizeClassName + '"></i>');
        $resize.css({
          position: 'absolute',
          right: '0px',
          bottom: '0px',
          border: '3px solid',
          cursor: 'nwse-resize'
        });
        $div.append($resize);
      }
      _this.$obj.append($div);
    };

    this.remove = function () {
      if(null == this.$select) return true;
      this.$select.remove();
      this.$select = null;
      return true;
    };

    this.addText = function (text) {
      var $item = $('<input>');
      $item.val(text);
      $item.on('input', function () {
        var fontSize = parseFloat($item.css('font-size').replace('px'));
        var $span = $('<span></span>');
        $span.css({
          visibility: 'hidden',
          'white-space': 'nowrap',
          'font-family': $item.css('font-family'),
          'font-size': $item.css('font-size'),
          'font-style': $item.css('font-style'),
          'font-weight': $item.css('font-weight'),
          'color': $item.css('color')
        });
        $span.text($item.val());
        $('body').append($span);
        $item.width($span.width());
        $item.height($span.height());
        $span.remove();
        $item.parent().width($item.width());
        $item.parent().height($item.height());
      }).trigger('input');
      this.addItem('text', $item, false);
    };

    this.addLogo = function (url) {
      var image = new Image();
      image.onload = function () {
        var boxWidth = _this.width * _this.options.logoMaxWidthRate;
        var boxHeigth = _this.height * _this.options.logoMaxHeigthRate;
        var adapt = _this.adapt(this.width, this.height, boxWidth, boxHeigth);
        var $item = $(image);
        $item.width(adapt.width);
        $item.height(adapt.height);
        _this.addItem('image', $item, true);
      }
      image.src = url;
    };

    this.namespace = function(name) {
      return name + '.' + _this.options.namespace;
    }

    this.select = function (dom) {
      this.$obj.children().each(function () {
        $(this).css({border: '1px hidden'});
      });
      if(null == dom) {
        this.$select = null;
      } else {
        this.$select = $(dom);
        this.$select.css({border: '1px dotted'});
      }
    }

    this.font = function ($item, param) {
      if(null == $item) {
        if(null == this.$select) return false;
        if('text' != this.$select.data('type')) return false;
        $item = this.$select.children('input');
      }
      $item.css({
        'font-family': param.family ? param.family : $item.css('font-family'),
        'font-size': param.size ? param.size : $item.css('font-size'),
        'font-style': param.style ? param.style : $item.css('font-style'),
        'font-weight': param.weight ? param.weight : $item.css('font-weight'),
        'color': param.color ? param.color : $item.css('color')
      });
      $item.trigger('input');
    };

    this.opacity = function ($item, opacity) {
      if(null == $item) {
        if(null == this.$select) return false;
        $item = this.$select;
      }
      opacity = parseFloat(opacity);
      opacity < 0.1 && (opacity = 0.1);
      opacity > 1.0 && (opacity = 1.0);
      $item.css({
        'opacity': opacity
      });
    };

  }

  $.fn.watermark = function (url, options) {
		options = $.extend({}, $.fn.watermark.defaults, options);
    var $watermark = new Watermark(this, url, options);

    (function (envname) { // 添加图标
      $(options.logoSelector).off(envname).on(envname, function () {
        $(options.fileSelector).val('');
        $(options.fileSelector).trigger('click');
      });
    })($watermark.namespace('click'));

    (function (envname) { // 获取图标内容
      $(options.fileSelector).off(envname).on(envname, function () {
        if(this.files.length < 1) return true;
        var reader = new FileReader();
        reader.onload = function(e) {
          $watermark.addLogo(e.target.result);
        };
        reader.readAsDataURL(this.files[0]);
      });
    })($watermark.namespace('change'));

    (function (envdown, envmove, envup) { // 移动
      var $item = null, ex = 0, ey = 0, ix = 0, iy = 0, width = 0, height = 0;

      $(document).off(envdown).on(envdown, '.' + $watermark.options.itemClassName, function (e) {
        $item = $(this);
        $watermark.select(this);
        e = e || window.event;
        ex = e.pageX;
        ey = e.pageY;
        ix = parseFloat($item.css('left').replace('px', ''));
        iy = parseFloat($item.css('top').replace('px', ''));
        width = $item.width();
        height = $item.height();
      });

      $(document).off(envmove).on(envmove, function (e) {
        if(null == $item) return true;
        e = e || window.event;
        var css = {
          top: (iy + e.pageY - ey),
          left: (ix + e.pageX - ex)
        };
        css.top < $watermark.top && (css.top = $watermark.top);
        css.top > ($watermark.top + $watermark.height - height - 2) && (css.top = $watermark.top + $watermark.height - height - 2);
        css.left < $watermark.left && (css.left = $watermark.left);
        css.left > ($watermark.left + $watermark.width - width - 2) && (css.left = $watermark.left + $watermark.width - width - 2);
        $item.css(css);
        e.preventDefault(); // 取消浏览器自身的拖动效果
      });

      $(document).off(envup).on(envup, function (e) {
        $item = null, ex = 0, ey = 0, ix = 0, iy = 0, width = 0, height = 0;
      });
    })($watermark.namespace('mousedown'), $watermark.namespace('mousemove'), $watermark.namespace('mouseup'));

    (function (envname) { // 选中&取消
      $(document).off(envname).on(envname, '.' + $watermark.options.itemClassName, function (e) {
        $watermark.select(this);
        $(options.opacitySelector).val(parseFloat($watermark.$select.css('opacity')) * 100);
        return false;
      });
      $watermark.$obj.off(envname).on(envname, function () {
        $watermark.select(null);
      });
    })($watermark.namespace('click'));

    (function (envdown, envmove, envup) { // 缩放
      var $item = null, ex = 0, ey = 0, ix = 0, iy = 0, width = 0, height = 0;

      $(document).off(envdown).on(envdown, '.' + $watermark.options.resizeClassName, function (e) {
        $item = $(this).parent();
        $watermark.select(this);
        e = e || window.event;
        ex = e.pageX;
        ey = e.pageY;
        ix = parseFloat($item.css('left').replace('px', ''));
        iy = parseFloat($item.css('top').replace('px', ''));
        width = $item.width();
        height = $item.height();
        return false;
      });

      $(document).off(envmove).on(envmove, function (e) {
        if(null == $item) return true;
        e = e || window.event;
        var css = {
          height: (height + e.pageY - ey),
          width: (width + e.pageX - ex)
        };
        css.width < $watermark.options.logoMimWidth && (css.width = $watermark.options.logoMimWidth);
        css.width > $watermark.width * $watermark.options.logoMaxWidthRate && (css.width = $watermark.width * $watermark.options.logoMaxWidthRate);
        css.height < $watermark.options.logoMimHeigth && (css.height = $watermark.options.logoMimHeigth);
        css.height > $watermark.height * $watermark.options.logoMaxHeigthRate && (css.height = $watermark.height * $watermark.options.logoMaxHeigthRate);
        $item.width(css.width);
        $item.height(css.height);
        e.preventDefault(); // 取消浏览器自身的拖动效果
        return false;
      });

      $(document).off(envup).on(envup, function (e) {
        $item = null, ex = 0, ey = 0, ix = 0, iy = 0, width = 0, height = 0;
        return false;
      });

    })($watermark.namespace('mousedown.resize'), $watermark.namespace('mousemove.resize'), $watermark.namespace('mouseup.resize'));

    (function (envname) { // 对齐操作
      $(options.alignSelector).off(envname).on(envname, function () {
        if($watermark.$select == null) return true;
        switch ($(this).data('orientation')) {
          case 'left-top':
          $watermark.$select.css({
            top: $watermark.top,
            left: $watermark.left
          });
          break;
          case 'center-top':
          $watermark.$select.css({
            top: $watermark.top,
            left: $watermark.left + ($watermark.width - $watermark.$select.width()) / 2
          });
          break;
          case 'rigth-top':
          $watermark.$select.css({
            top: $watermark.top,
            left: $watermark.left + $watermark.width - $watermark.$select.width() - 2
          });
          break;
          case 'center-center':
          $watermark.$select.css({
            top: $watermark.top + ($watermark.height - $watermark.$select.height()) / 2 - 1,
            left: $watermark.left + ($watermark.width - $watermark.$select.width()) / 2 - 1
          });
          break;
          case 'left-bottom':
          $watermark.$select.css({
            top: $watermark.top + $watermark.height - $watermark.$select.height() - 2,
            left: $watermark.left
          });
          break;
          case 'center-bottom':
          $watermark.$select.css({
            top: $watermark.top + $watermark.height - $watermark.$select.height() - 2,
            left: $watermark.left + ($watermark.width - $watermark.$select.width()) / 2
          });
          break;
          case 'rigth-bottom':
          $watermark.$select.css({
            top: $watermark.top + $watermark.height - $watermark.$select.height() - 2,
            left: $watermark.left + $watermark.width - $watermark.$select.width() - 2
          });
          break;
        }
      });
    })($watermark.namespace('click'));

    (function (envname) { // 添加文字
      $(options.textSelector).off(envname).on(envname, function () {
        $watermark.addText(options.textTips);
      });
    })($watermark.namespace('click'));

    (function (envname) { // 删除所选
      $(options.removeSelector).off(envname).on(envname, function () {
        $watermark.remove();
      });
    })($watermark.namespace('click'));

    (function (envname) { // 设置字体
      $(options.fontFamilySelector).off(envname).on(envname, function () {
        $watermark.font(null, {
          family: $(this).data('family')
        });
      });
    })($watermark.namespace('click'));

    (function (envname) { // 设置字号
      $(options.fontSizeSelector).off(envname).on(envname, function () {
        $watermark.font(null, {
          size: $(this).data('size')
        });
      });
    })($watermark.namespace('click'));

    (function (envname) { // 设置字体样式
      $(options.fontStyleSelector).off(envname).on(envname, function () {
        $watermark.font(null, {
          style: $(this).data('style')
        });
      });
    })($watermark.namespace('click'));

    (function (envname) { // 设置字体粗细
      $(options.fontWeightSelector).off(envname).on(envname, function () {
        $watermark.font(null, {
          weight: $(this).data('weight')
        });
      });
    })($watermark.namespace('click'));

    (function (envname) { // 设置颜色
      $(options.colorSelector).off(envname).on(envname, function () {
        $watermark.font(null, {
          color: $(this).data('color')
        });
      });
    })($watermark.namespace('click'));

    (function (envname) { // 透明度
      $(options.opacitySelector).off(envname).on(envname, function () {
        $watermark.opacity(null, $(this).val() / 100);
      });
    })($watermark.namespace('change'));

    return $watermark;
  };
  
  $.fn.watermark.defaults = {
    resizeClassName: 'watermark-resize',
    itemClassName: 'watermark-item',
    namespace: 'watermark',
    textTips: '请输入文字内容...',
    loading: '载入中...',
    logoMimWidth: 15,
    logoMimHeigth: 15,
    logoMaxWidthRate: 0.35,
    logoMaxHeigthRate: 0.35,
    alignSelector: '.watermark-align',
    textSelector: '.watermark-text',
    removeSelector: '.watermark-remove',
    fontFamilySelector: '.watermark-font-family',
    fontSizeSelector: '.watermark-font-size',
    fontStyleSelector: '.watermark-font-style',
    fontWeightSelector: '.watermark-font-weight',
    colorSelector: '.watermark-color',
    opacitySelector: '.watermark-opacity',
    logoSelector: '.watermark-logo',
    fileSelector: '.watermark-file'
  };

})(jQuery);
