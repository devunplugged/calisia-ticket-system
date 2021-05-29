jQuery(document).ready(function() {
    const user_search_input = document.getElementById('ticket-user-search');
    const element_search_input = document.getElementById('ticket-element-search');
    const kind_select_input = document.getElementById('kind-select');

    let calisiaSearch = new CalisiaSearch();
    let calisiaUI = new CalisiaUI();

    user_search_input.addEventListener(
        'input', 
        event=>{
            document.querySelector('#user-suggestion').style.display = 'block';
            document.querySelector('#user-suggestion').innerText = 'loading results...';
            calisiaSearch.searchElement(event.target.value, 'user');
        }, 
        false
    );
    element_search_input.addEventListener(
        'input', 
        event=>{
            document.querySelector('#element-suggestion').innerText = 'loading results...';
            calisiaSearch.searchElement(event.target.value, document.querySelector('#kind-select').value);
        },
        false
    );
    kind_select_input.addEventListener(
        'input', 
        ()=>{
            document.querySelector('#element-suggestion').innerText = 'loading results...';
            calisiaUI.kindChanged(document.querySelector('#ticket-element-search').value, document.querySelector('#kind-select').value, calisiaSearch);
        },
        false
    );
    
    
    document.querySelector('#select-other-user-button').addEventListener('click', calisiaUI.showUserSearch, false);
    document.querySelector('#select-other-element-button').addEventListener('click', calisiaUI.showElementSearch, false);
});

class CalisiaSearch{
    
    constructor(){
        //retain 'this' value
        this.searchUsers = this.searchElement.bind(this);
        this.scheduled_search;
    }

    searchElement(phrase, kind){
        console.log("type: "+kind);
        let self = this;
        clearTimeout(this.scheduled_search);
        this.scheduled_search = setTimeout(function (){self.searchPhrase(phrase, kind)}, 1000);
    }

    searchPhrase(phrase, kind){
        console.log("Begin search: "+phrase+ " type:" +kind);
        let ajax = new CalisiaAjax();
        ajax.call(
            { 
                action: 'calisia_ticket_system_search', // this is the function in your functions.php that will be triggered
                phrase: phrase,
                kind: kind,
                user_id: document.querySelector('#ticket-user-id').value
            },
            function (data){
                let renderer = new CalisiaUI();
                if(kind == 'user'){
                    renderer.showUsers(data);
                }else{
                    renderer.showElements(data);
                }
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

    kindChanged(phrase, kind, calisiaSearch){
        if(kind == 'order'){
            document.querySelector('.element-search-container').style.display = 'block';
            calisiaSearch.searchElement(phrase, kind);
        }else{
            document.querySelector('.element-search-container').style.display = 'none';
        }
    }

    showUsers(data){
        console.log("data:");
        console.log(data.results);
        
       // users.forEach(this.showUser);
        document.querySelector("#user-suggestion").innerHTML = '';
        Object.values(data.results).forEach(this.showUser);
        let elements = document.querySelectorAll(".user-select");
        elements.forEach(element => {
            element.addEventListener('click', this.pickUser, false);
        });
    }

    showUser(user){
        let container = document.querySelector("#user-suggestion");
        let newNode = document.createElement('div');
        newNode.classList.add('suggestion-row');

        let userName = document.createElement('div');
        userName.classList.add("suggestion-username");
        userName.innerText = user.user_email;
        newNode.appendChild(userName);

        let link = document.createElement('a');
        link.classList.add("button");
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

        document.querySelector('.ticket-user-search-container').style.display = 'none';
        document.querySelector('#user-suggestion').style.display = 'none';
        document.querySelector('.ticket-selected-user').style.display = 'block';
    }



    showUserSearch(){
        document.querySelector('.ticket-user-search-container').style.display = 'block';
        document.querySelector('#user-suggestion').style.display = 'block';
    }

    showElements(data){
        console.log("recived data:");
        console.log(data.results);

        document.querySelector('#element-suggestion').style.display = 'block';
        document.querySelector("#element-suggestion").innerHTML = '';
        Object.values(data.results).forEach(this.showElement);
        let elements = document.querySelectorAll(".element-select");
        elements.forEach(element => {
            element.addEventListener('click', this.pickElement, false);
        });
    }

    showElement(element){
        let container = document.querySelector("#element-suggestion");
        let newNode = document.createElement('div');
        newNode.classList.add('suggestion-row');

        let elementId = document.createElement('div');
        elementId.classList.add("element-id","suggestion-row-element");
        elementId.innerText = element.order_id;
        newNode.appendChild(elementId);

        let orderStatus = document.createElement('div');
        orderStatus.classList.add("element-status","suggestion-row-element");
        orderStatus.innerText = element.order_status;
        newNode.appendChild(orderStatus);

        let orderDate = document.createElement('div');
        orderDate.classList.add("element-date","suggestion-row-element");
        orderDate.innerText = element.order_date;
        newNode.appendChild(orderDate);

        let orderTotal = document.createElement('div');
        orderTotal.classList.add("element-total","suggestion-row-element");
        orderTotal.innerText = element.order_total;
        newNode.appendChild(orderTotal);


        let orderActions = document.createElement('div');
        orderActions.classList.add("element-total","suggestion-row-element");
        newNode.appendChild(orderActions);

        let link = document.createElement('a');
        link.innerText = 'select';
        link.dataset.id = element.order_id;
        link.classList.add("element-select");
        link.classList.add("button");
        orderActions.appendChild(link);

        container.appendChild(newNode);
    }

    pickElement(){
        console.log('data-id: ' + this.dataset.id);
        document.querySelector('#ticket-element-id').value = this.dataset.id;
        document.querySelector('#selected-element-info').innerText = '#' + this.dataset.id;

        document.querySelector('#ticket-element-search').style.display = 'none';
        document.querySelector('#element-suggestion').style.display = 'none';
        document.querySelector('.ticket-selected-element').style.display = 'block';
    }

    showElementSearch(){
        document.querySelector('#ticket-element-search').style.display = 'block';
        document.querySelector('#element-suggestion').style.display = 'block';
    }
}