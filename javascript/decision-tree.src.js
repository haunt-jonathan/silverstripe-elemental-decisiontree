(function() {

    const serialize = function(formData) {
        let str = Array.from(formData).map(entry => entry.map(encodeURIComponent).join("=")).join("&");
        if (str) {
            str += "&ajax=1";
        }
        return str;
    }

    const scrollTree = function(tree) {
        var html = document.querySelector('html');
        var body = document.querySelector('body');

        var currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        var targetScroll = tree.getBoundingClientRect().top + currentScroll - 150;

        var animateScroll = function(currentTime, start, change, duration) {
            return (-change * (currentTime /= duration) * (currentTime - 2)) + start;
        };

        var duration = 500;
        var start = currentScroll;
        var change = targetScroll - start;
        var startTime = performance.now();

        var step = function() {
            var currentTime = performance.now() - startTime;
            if (currentTime < duration) {
                var newScroll = animateScroll(currentTime, start, change, duration);
                html.scrollTop = newScroll;
                body.scrollTop = newScroll;
                requestAnimationFrame(step);
            } else {
                html.scrollTop = targetScroll;
                body.scrollTop = targetScroll;
            }
        };

        requestAnimationFrame(step);
    }

    document.addEventListener('DOMContentLoaded', function () {
        /**
        * Submit Step form when selecting an answer
        * Adds loading animation to empty "nextstep" div
        * Fetch content via ajax
        * Insert HTML and update URL
        */
        
        document.addEventListener('change', function(ev) {
            var inputs = document.querySelectorAll('input[name=stepanswerid]');
            if (Array.from(inputs).indexOf(ev.target) >= 0) {
                var form = ev.target.closest('form'),
                    step = form.closest('.step'),
                    nextstep_holder = step.querySelector('.nextstep');

                    nextstep_holder.innerHTML = '<div class="spinner-holder"><div class="spinner"><span class="sr-only">loading</span></div></div>';
                    setTimeout(function() {
                        nextstep_holder.classList.add('loading');
                    }, 100);

                    let url = form.getAttribute('action').split('?');
                    if (url.length > 1) {
                        url = url[0];
                    }
                    if (! /\/$/.test(url)) {
                        url += '/';
                    }
                    let xhr = new XMLHttpRequest();
                    xhr.open('POST', url, true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.responseType = 'json';
                    xhr.send(serialize(new FormData(form)));
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            var data = xhr.response;
                            nextstep_holder.classList.add('new-content-loaded');
                            nextstep_holder.innerHTML = data.html;
                            window.history.pushState(null, null, data.nexturl);
                        } else {
                            nextstep_holder.innerHTML = xhr.responseText;
                        }
                    };
                }
            }
        );

        /**
        * Handles the restart button
        * Empties all subsequent steps then
        * Scroll back to first step then
        * Reset url to original page url
        */
        document.addEventListener('click', function(ev) {
            var buttons = document.querySelectorAll('button[data-action="restart-tree"]');
            if (Array.from(buttons).indexOf(ev.target) >= 0) {
                var button = ev.target,
                firststep = button.closest('.step--first'),
                radios = firststep.querySelectorAll('input[type="radio"]'),
                tree = firststep.closest('.decisiontree');

                if (firststep) {

                    var nextstep = firststep.querySelector('.nextstep');
                    
                    nextstep.style.opacity = 0;
                    
                    var fadeOutCallback = function() {
                        nextstep.innerHTML = '';
                        nextstep.style.display = 'block';
                        nextstep.style.opacity = 1;
                        radios.forEach(element => {
                            element.checked = false; 
                        });
                        scrollTree(tree);
        
                        const url = location.protocol + '//' + location.host + location.pathname;
                        window.history.pushState(null, null, url);
                    };
                    
                    setTimeout(fadeOutCallback, 500);
                    
                }
    
            }
        });
    }, false);
})();
