app.directive('customFileChange', function() {
      return {
        restrict: 'A',
        link: function (scope, element, attrs) {
                var onChangeFunc = scope.$eval(attrs.customOnChange);
                element.bind('change', onChangeFunc);
                console.log(element);
            }
    };
});