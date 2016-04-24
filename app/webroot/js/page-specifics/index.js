'use strict';

var activitiesBlockMaker = {
    $container: '',
    blankImageDiv: '<div><img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="></div>',
    init: function($container, content) {
        var newInstance = Object.create(this);
        newInstance.$container = $container;
        if (typeof content === 'object') newInstance.make(content);
        return newInstance;
    },
    make: function(content) {
        var html = '';
        if (Array.isArray(content)) {
            for(var i=0; i<content.length; i++) {
                html += this.generator(content[i]);
            }
        } else if (typeof content === 'object') {
            html = this.generator(content);
        }
        return this.$container.append(html);
    },
    generator: function(eachContent) {

        var html = '';
        html += '<div class="activitiesBlocks' + this.sizeClass(eachContent.blockSize) + '" style="' + this.floatDictator(eachContent.blockSize) + '">';
        html += '    <div class="activitiesFiller">' + this.makeBlankDivs(eachContent.blockSize) + '</div>';
        
        html += '    <div class="activitiesOverlayOuter">';
        html += '        <div class="activitiesOverlayInner"><a href="'+eachContent.urlPath+'">';
        html += '            <img class="activitiesFeaturedImg" alt="'+eachContent.title+'" src="' + eachContent.imagePath + '">';
        html += '            <div class="activitiesTable">';
        
        html += '                <div class="activitiesCell">';
        html += '                    <h3 class="activitiesHeader headerAlt">' + eachContent.title + '</h3>';
        html += '                </div>';
        html += '            </div>';
        html += '          </a></div>'
        html += '    </div>';
        html += '</div>';

        return html;
    },
    makeBlankDivs: function(blockSize) {
        var blankDivs = '';
        var computedSize = this.computeSize(blockSize);
        for(var i=0; i<computedSize; i++) {
            blankDivs += this.blankImageDiv;
        }
        return blankDivs;
    },
    sizeClass: function(size) {
        if (!size || size === '1x1') return ' activities1x1';
        return ' activities' + size;
    },
    computeSize: function(size) {
        if (!size || size === '1x1') return 1;
        var factors = this.computeFactors(size);
        var computedSize = parseFloat(factors[0]) * parseFloat(factors[1]);
        if (isNaN(computedSize) || factors.length !== 2) throw new Error('blockSize is invalid.');
        return computedSize;
    },
    computeFactors: function(size) {
        if (!size || size === '1x1') return [1, 1];
        var factors = size.split('x');
        for(var i in factors) {
            factors[i] = parseFloat(factors[i]);
        }
        return factors;
    },
    /* only compatible with 2x1 and 2x2 blocks, very naive function */
    floatDictator: function(blockSize) {
        var factors = this.computeFactors(blockSize);
        if (this.floatCounter === 2 && factors[1] === 2) return 'float: right;';
        this.addFloatCounter(factors);
        return '';
    },
    addFloatCounter: function(factors) {
        this.floatCounter = this.floatCounter || 0;

        if (factors[0] === 2 && factors[1] === 2) {
            if (this.floatCounter < 2) this.floatCounter += factors[0];
            else this.floatCounter = 0;
        } else {
            this.floatCounter += factors[0];
            var overflow = this.floatCounter - 4;
            if (overflow > -1) this.floatCounter = overflow;
        }
    }
};

/* VIDEO CROPPER */

var VideoCropper = {
    $target: '',
    aspectRatio: 1280/720,
    init: function($target, aspectRatio) {
        // create new object
        var newObject = Object.create(this);
        // initialise it.
        newObject.$target = $target;
        if (typeof aspectRatio !== 'undefined') newObject.aspectRatio = aspectRatio;
        newObject.setWrapperProperties();
        newObject.resize();
        newObject.binder();
        return newObject;
    },
    setWrapperProperties: function() {
        this.$target.parent().css({
            'width': '100%',
            'overflow': 'hidden'
        });
    },
    binder: function() {
        var self = this;
        $(window).on('throttledresize', function() {
            self.resize();
        });
    },
    resize: function() {
        var viewPortHeight = $(window).height();
        var viewPortWidth = $(window).width();
        var viewPortAspectRatio = viewPortWidth / viewPortHeight;

        // if window is wider than aspect ratio
        if (viewPortAspectRatio >= this.aspectRatio) {

            this.$target.css({
                'marginLeft': '',
                'width': '100%',
                'height': 'auto'
            });
            return;

        }

        // window is very tall
        if (viewPortHeight > viewPortWidth) {
            var videoWidth = this.calculateVideoWidth(viewPortWidth);

            this.$target.css({
                'marginLeft': this.calculateLeftDisplacement(viewPortWidth, videoWidth),
                'height': this.calculateMaxHeight(videoWidth),
                'width': 'auto'
            });
            return;

        }
        // intermediate sizes, just displace normally
        this.$target.css({
            'marginLeft': this.calculateLeftDisplacement(viewPortWidth, viewPortHeight * this.aspectRatio),
            'height': viewPortHeight,
            'width': 'auto'
        });

    },
    calculateVideoWidth: function(w) {
        return w * this.aspectRatio;
    },
    calculateLeftDisplacement: function(viewPortWidth, videoWidth) {
        return (-(videoWidth - viewPortWidth)/2).toString() + 'px';
    },
    calculateMaxHeight: function(videoWidth) {
      return videoWidth / this.aspectRatio;
    }
};

// run it
$(function() {
    var cropper = VideoCropper.init($('#splashVideoCont video, #splashVideoCont img'));
});




