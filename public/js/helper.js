function convertDateToDateTime(date) {
    let m = new Date(date);
    return ("0" + m.getUTCDate()).slice(-2) + "/" +
        ("0" + (m.getUTCMonth() + 1)).slice(-2) + "/" +
        m.getUTCFullYear() + " " +
        ("0" + m.getUTCHours()).slice(-2) + ":" +
        ("0" + m.getUTCMinutes()).slice(-2);
}

// function renderPagination(links) {
//     links.forEach(function(each){
//         <li class="page-item">
//             <a class="page-link" href=""></a>
//         </li>
//         $('#pagination').append($('<li>')
//             .append(`<a class="page-link ${each.active ? 'active' : ''}" href="${each.url}">
//                 ${each.label}
//             </a>`));
//     })
// }

function renderPagination(links) {
    links.forEach(function (each) {
        $('#pagination').append($('<li>').attr('class', `page-item ${each.active ? 'active' : ''}`)
            .append(`<a class="page-link" >${each.label}</a>`));
    })
}
// href="${each.url}" 

function notifySuccess(message = '') {
    $.toast({
        heading: 'Success',
        text: message,
        showHideTransition: 'slide',
        position: 'bottom-right',
        icon: 'success'
    })
}
function notifyError(message = '') {
    $.toast({
        heading: 'Error',
        text: message,
        showHideTransition: 'slide',
        position: 'bottom-right',
        icon: 'error'
    })
}