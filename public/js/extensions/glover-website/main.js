document.addEventListener('DOMContentLoaded', function () {
    livewire.on('reloadpage', function (message) {
        //reload page
        location.reload();
    });
});