/******************************************************************************/
// Created by: Shlomo Hassid.
// Release Version : 1.0.1
// Creation Date: 17/05/20202
// Copyright 2020, Shlomo Hassid.
/******************************************************************************/
/*****************************      Changelog       ****************************
1.0.1:
    ->intial, Creation
*******************************************************************************/

;
(function($, window, document, undefined) {
    /******************************  The Plugin class  *****************************/
    class sikBg {
        static pluginName = 'sikBg';
        /******************************  Defaults  *****************************/
        defaults = {
            width: 150,
            height: 150,
            connections: 5,
            pointsRatio: 10,
            circleSize: { add: 2, multi: 5 },
            shift: { move: 50, multi: 100 },
            speed: { add: 0.5, multi: 2 },
            regenerateConnections: false,
            runAnimate: true,
            BackgroundColor: "transparent"
        };
        /******************************  Members  *****************************/
        canvas = null;
        ctx = null;
        points = [];
        target = { x: 0, y: 0 };
        static speed;
        static shift;
        static size = { w: 0, h: 0 };
        constructor(element, options) {
            this.element = element;
            this.$element = $(element);
            /******************************  Extend  *****************************/
            this.options = $.extend({}, this.defaults, options);
            sikBg.speed = this.options.speed;
            sikBg.shift = this.options.shift;
            /******************************  Run  *****************************/
            this.init();
        };
        /******************************  Private variables  *****************************/
        //var privatevar1 = '';
        /******************************  Init  *****************************/
        init() {
            this.$element.css({ position: "absolute", top: 0, left: 0 });
            this.options.width = this.$element.parent().outerWidth();
            this.options.height = this.$element.parent().outerHeight();
            sikBg.size.w = this.options.width;
            sikBg.size.h = this.options.height;
            this.target = { x: this.options.width / 3, y: this.options.height / 1.5 };
            this.canvas = this.element;
            this.canvas.width = this.options.width;
            this.canvas.height = this.options.height;
            this.ctx = this.canvas.getContext('2d');
            this._createPoints();
            this._setNearByPoints();
            this._addCirclesToPoints();
            this._initAnimation();
            this._addListeners();
        };
        /******************************  Public Methods  *****************************/
        test() {
            console.log("public");
        };
        /******************************  Private Methods  *****************************/
        // create random points:
        _createPoints() {
            var ratio = this.options.pointsRatio;
            for (var x = 0; x < this.options.width; x = x + this.options.width / ratio) {
                for (var y = 0; y < this.options.height; y = y + this.options.height / ratio) {
                    var px = x + Math.random() * this.options.width / ratio;
                    var py = y + Math.random() * this.options.height / ratio;
                    var p = { x: px, originX: px, y: py, originY: py };
                    this.points.push(p);
                }
            }
        };
        // Attach Points:
        _setNearByPoints() {
            for (var i = 0; i < this.points.length; i++) {
                var closest = [];
                var p1 = this.points[i];
                for (var j = 0; j < this.points.length; j++) {
                    var p2 = this.points[j];
                    if (!(p1 == p2)) {
                        var placed = false;
                        for (var k = 0; k < this.options.connections; k++) {
                            if (!placed) {
                                if (closest[k] == undefined) {
                                    closest[k] = p2;
                                    placed = true;
                                }
                            }
                        }
                        for (var k = 0; k < this.options.connections; k++) {
                            if (!placed) {
                                if (this._getDistance(p1, p2) < this._getDistance(p1, closest[k])) {
                                    closest[k] = p2;
                                    placed = true;
                                }
                            }
                        }
                    }
                }
                p1.closest = closest;
            }
        };
        // calculate distance:
        _getDistance(p1, p2) {
            return Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2);
        };
        // create circles:
        _addCirclesToPoints() {
            // assign a circle to each point
            for (var i in this.points) {
                var c = new Circle(
                    this.points[i],
                    this.options.circleSize.add + Math.random() * this.options.circleSize.multi,
                    'rgba(255,255,255,0.1)'
                );
                this.points[i].circle = c;
            }
        };
        _initAnimation() {
            this._animate();
            for (var i in this.points) {
                sikBg._shiftPoint(this.points[i]);
            }
        };
        _animate() {
            if (this.options.runAnimate) {
                var mostBright = 0.3;
                if (this.options.regenerateConnections) this._setNearByPoints();
                this.ctx.clearRect(0, 0, this.options.width, this.options.height);
                for (var i in this.points) {
                    // detect points in range
                    if (Math.abs(this._getDistance(this.target, this.points[i])) < 10000) {
                        this.points[i].active = mostBright * 0.5;
                        this.points[i].circle.active = mostBright;
                    } else if (Math.abs(this._getDistance(this.target, this.points[i])) < 40000) {
                        this.points[i].active = mostBright * 0.2;
                        this.points[i].circle.active = mostBright * 0.5;
                    } else if (Math.abs(this._getDistance(this.target, this.points[i])) < 80000) {
                        this.points[i].active = mostBright * 0.1;
                        this.points[i].circle.active = mostBright * 0.2;
                    } else {
                        this.points[i].active = 0;
                        this.points[i].circle.active = 0;
                    };
                    this._drawLines(this.points[i]);
                    this.points[i].circle.draw(this.ctx);
                }
            }
            //requestAnimationFrame(this._animate);
            requestAnimationFrame(() => this._animate());
        };
        static _shiftPoint(p) {
            var shiftPos = {
                x: p.originX - sikBg.shift.move + (Math.random() * sikBg.shift.multi),
                y: p.originY - sikBg.shift.move + (Math.random() * sikBg.shift.multi),
                ease: Back.easeInOut,
                onComplete: function() { sikBg._shiftPoint(p); }
            };
            if (shiftPos.x < 10) {
                shiftPos.x = 10 * Math.random() + 10;
            } else if (shiftPos.x > sikBg.size.w - 10) {
                shiftPos.x = sikBg.size.w - (10 * Math.random() + 10);
            }
            if (shiftPos.y < 10) {
                shiftPos.y = 10 * Math.random() + 10;
            } else if (shiftPos.y > sikBg.size.h - 10) {
                shiftPos.y = sikBg.size.h - (10 * Math.random() + 10);
            }
            /*
            while (true) {
                if (shiftPos.x > sikBg.size.w) {
                    shiftPos.x = p.originX - sikBg.shift.move + (Math.random() * sikBg.shift.multi);
                } else if (shiftPos.y > sikBg.size.h) {
                    shiftPos.y = p.originY - sikBg.shift.move + (Math.random() * sikBg.shift.multi);
                } else {
                    break;
                }
            }
            */
            TweenLite.to(
                p,
                sikBg.speed.add + (sikBg.speed.multi * Math.random()),
                shiftPos
            );
        };
        // Canvas manipulation
        _drawLines(p) {
            if (!p.active) return;
            for (var i in p.closest) {
                this.ctx.beginPath();
                this.ctx.moveTo(p.x, p.y);
                this.ctx.lineTo(p.closest[i].x, p.closest[i].y);
                this.ctx.strokeStyle = 'rgba(156,217,249,' + p.active + ')';
                this.ctx.stroke();
            }
        };
        // Event handling
        _addListeners() {
            if (!('ontouchstart' in window)) {
                //window.addEventListener('mousemove', e => this._mouseMove(e));
            }
            window.addEventListener('scroll', () => this._scrollCheck());
            window.addEventListener('resize', () => this._resize());
        };
        _mouseMove(e) {
            let posx = 0,
                posy = 0;
            if (e.pageX || e.pageY) {
                posx = e.pageX;
                posy = e.pageY;
            } else if (e.clientX || e.clientY) {
                posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
                posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
            }
            this.target.x = posx;
            this.target.y = posy;
        };
        _scrollCheck() {
            if (document.body.scrollTop > this.options.height) this.options.runAnimate = false;
            else this.options.runAnimate = true;
        };
        _resize() {
            this.options.width = this.$element.parent().outerWidth();
            this.options.height = this.$element.parent().outerHeight();
            this.canvas = this.element;
            this.canvas.width = this.options.width;
            this.canvas.height = this.options.height;
        };
    }

    class Circle {
        active = 0;
        constructor(pos, rad, color) {
            // constructor
            this.pos = pos || null;
            this.radius = rad || null;
            this.color = color || null;
        }
        draw(ctx) {
            if (!this.active)
                return;
            ctx.beginPath();
            ctx.arc(this.pos.x, this.pos.y, this.radius, 0, 2 * Math.PI, false);
            ctx.fillStyle = 'rgba(156,217,249,' + this.active + ')';
            ctx.fill();
        };
    }

    $.fn[sikBg.pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + sikBg.pluginName)) {
                $.data(this, 'plugin_' + sikBg.pluginName,
                    new sikBg(this, options));
            }
        });
    }

})(jQuery, window, document);

$("#demo-canvas").sikBg({
    connections: 3,
    pointsRatio: 10,
    circleSize: { add: 1, multi: 5 },
    shift: { move: 50, multi: 100 },
    speed: { add: 4, multi: 2 },
    runAnimate: true,
    regenerateConnections: false,
    BackgroundColor: "transparent"
});