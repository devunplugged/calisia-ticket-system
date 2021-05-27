jQuery(document).ready(function() {
    const user_search_input = document.getElementById('ticket-user-search');
    let calisiaSearch = new CalisiaSearch();

    user_search_input.addEventListener('input', calisiaSearch.searchUsers, false);
    
    let calisiaUI = new CalisiaUI();
    document.querySelector('#select-other-user-button').addEventListener('click', calisiaUI.showUserSearch, false);
});

class CalisiaSearch{
    
    constructor(){
        //retain 'this' value
        this.searchUsers = this.searchUsers.bind(this);
        this.scheduled_search;
    }

    searchUsers(event){
        let self = this;
        clearTimeout(this.scheduled_search);
        this.scheduled_search = setTimeout(function (){self.searchPhrase(event.target.value)}, 1000);
    }

    searchPhrase(phrase){
        let ajax = new CalisiaAjax();
        ajax.call(
            { 
                action: 'calisia_user_search', // this is the function in your functions.php that will be triggered
                phrase: phrase
            },
            function (data){
                let renderer = new CalisiaUI();
                renderer.showUsers(data);
            }
        );
    }

}

class CalisiaAjax{
    call(dataObject, callback){
        jQuery.ajax({
            url: "admin-ajax.php",
            type: 'POST',
            data: dataObject,
            success: function( data ){
                //Do something with the result from server
                console.log("start raw data:");
                console.log(data);
                console.log("end raw data");
                data = JSON.parse(data);
                callback(data);
            }
        });
    }
}

class CalisiaUI{
    constructor(){
        //retain 'this' value
        this.showUsers = this.showUsers.bind(this);
    }

    showUsers(users){
        console.log("data:");
        console.log(users.users);
       // users.forEach(this.showUser);
        document.querySelector("#user-suggestion").innerHTML = '';
        Object.values(users.users).forEach(this.showUser);
        let elements = document.querySelectorAll(".user-select");
        elements.forEach(element => {
            element.addEventListener('click', this.pickUser, false);
        });
    }

    showUser(user){
        let container = document.querySelector("#user-suggestion");
        let newNode = document.createElement('div');

        let userName = document.createElement('div');
        userName.classList.add("suggestion-username");
        userName.innerText = user.user_email;
        newNode.appendChild(userName);

        let link = document.createElement('a');
        link.innerText = 'select';
        link.dataset.id = user.ID;
        link.dataset.firstName = user.first_name;
        link.dataset.lastName = user.last_name;
        link.dataset.email = user.user_email;
        link.classList.add("user-select");
        newNode.appendChild(link);

        container.appendChild(newNode);
    }

    pickUser(){
        console.log('data-id: ' + this.dataset.id);
        document.querySelector('#ticket-user-id').value = this.dataset.id;
        document.querySelector('#selected-user-info').innerText = this.dataset.email + ' ' + this.dataset.firstName + ' ' + this.dataset.lastName;
        jQuery('#ticket-user-search').hide();
        jQuery('#user-suggestion').hide();
        jQuery('#select-other-user-button').show();
    }



    showUserSearch(){
        jQuery('#ticket-user-search').show();
        jQuery('#user-suggestion').show();
    }
}