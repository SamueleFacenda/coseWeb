
function editModal(id){
    let text = document.getElementById(id).getElementsByTagName('p')[0].innerHTML;
    let label = document.getElementById(id).getElementsByTagName('h5')[0].innerHTML;
    // replace <br> with new line
    text = text.trim().replace(/<br>/g, '');
    label = label.trim();

    let area = document.getElementById('textEdit');

    document.getElementById('labelEdit').value = label;
    document.getElementsByName('note_id').forEach((e) => {e.value = id});
    area.value = text;
    let myModal = new bootstrap.Modal(document.getElementById('editModal'), {});
    myModal.show();

    document.getElementById('search').value = '';
}

function updateTextArea(){
    let area = document.getElementById('textEdit');
    area.style.height = 'auto';
    area.style.height = '' + (area.scrollHeight + 12) + 'px';
}

function updateSearch(){
    // ajaj request
    let query = document.getElementById('search').value;
    let list = document.getElementById('searchList');
    if (query.length < 1){
        list.innerHTML = '';
        return;
    }
    let url = '/api/search_users.php?query=' + query;
    fetch(url)
    .then(response => response.json())
    .then(data => {
        list.innerHTML = '';
        data.forEach((e) => {
            let el = document.createElement('button');
            el.className = 'list-group-item list-group-item-action';
            el.innerHTML = e.email;
            el.onclick = () => {
                document.getElementById('search').value = e.email;
                list.innerHTML = '';
            }
            list.appendChild(el);
        });
    });

}