angular
.module('hris', [])
.controller('TimeSheetController', function ($scope, $http) {
    
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({
            html: true,
            template: '<div class="tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
        });   
    });
});