$(document).ready(function () {
    $('.delete-button').click(function () {
            deleteProject(this);
        }
    );
});

function deleteProject(deleteButton) {
    var link = $(deleteButton).attr('data-href');
    $('#delete').click(function() {
            window.location.href = link;
        }
    );
}