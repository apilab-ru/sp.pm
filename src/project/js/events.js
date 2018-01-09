(function () {
    window.events = new function () {

        var list = {};
        var queue = {};

        this.trigger = function (type, data) {
            if (list[type]) {
                for (let key in list[type]) {
                    list[type][key](data);
                };
            } else {
                if (!queue[type]) {
                    queue[type] = [];
                };
                queue[type].push(data);
            };
        };

        this.add = function (type, callback) {
            if (!list[type]) {
                list[type] = [];
            }
            list[type].push(callback);
            if (queue[type]) {
                for (let key in queue[type]) {
                    callback(queue[type][key]);
                }
                delete(queue[type]);
            };
        }
    };
})();