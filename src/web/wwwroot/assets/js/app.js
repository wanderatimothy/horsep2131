var Table = function (columns, $element, dataSrc, callback = null) {

    this.columns = columns

    this.element = document.getElementById($element);

    this.url = dataSrc.url;

    this.token = dataSrc.token;

    this.name = $element

    this.dataCount = 0

    this.callback = callback

    this.countElement = document.getElementById(this.name + '-counter')


    const dataHasChanged = new CustomEvent('data_set_has_changed', { detail : { should_affect : this.element  }   })
    
    this.dataSet = [] 

    this.set_data = function(dataSet){
        
        if(this.dataSet.length != dataSet.length){
            this.dataSet = dataSet
            dispatchEvent(dataHasChanged)
        }
    }

    
    this.creatHeadings = function () {

        this.element.classList = "table align-items-center text-center"
        let thead = document.createElement('thead')
        let tr = document.createElement("tr")
        this.columns.forEach(element => {
            let th = document.createElement('th')
            th.textContent = element.title
            th.classList = "text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
            tr.appendChild(th)
        });
        thead.appendChild(tr)
        this.element.appendChild(thead)

    }

    this.createTableBody = function(){
        let tbody = document.createElement("tbody")
        tbody.id = this.name + '-tbody-section';
        this.element.appendChild(tbody)
    }

    this.creatHeadings();
    this.createTableBody();
    
    addEventListener('data_set_has_changed',(e)=>{
        this.updateBody();
        if(typeof callback == "function" ) this.callback(this.dataSet) 
    })

    this.updateBody = function () {

        let body = document.getElementById(this.name + '-tbody-section');
        RemoveAllChildren(body)

        if (this.dataSet.length == 0) {
            let row = document.createElement("tr")
            let td = document.createElement("td")
            td.setAttribute("colspan", this.columns.length)
            td.classList = "text-capitalize py-2 text-muted";
            td.textContent = "No Items to Show"
            row.appendChild(td)
            body.appendChild(row)
            this.countElement.innerHTML = 0
        } else {
            this.dataCount = this.dataSet.length
            this.dataSet.forEach((row, $index) => {

                let tr = document.createElement("tr")

                this.columns.forEach((item) => {

                    let td = document.createElement('td')
                    td.innerHTML = typeof item.data == "function" ? item.data(row, $index) : row[item.name]
                    tr.appendChild(td)
                })

                body.appendChild(tr)
                this.countElement.innerHTML = `${1 + $index}`
            })
        }

        return true;
    }


    this.init = function () {
        this.fetch();
    }
    this.update = function () {
        this.init()
    }

    this.fetch = function () {

        $.ajax({
            method: 'GET',
            url: this.url,
            contentType: 'Json',
            cache: true,
            headers: {
                'Authentication': this.token
            },
            success: response => this.set_data(response.data),
            error: (xhr, status) => {
                $.notify('error', xhr.responseJSON.data.errors['info'])
                console.log(xhr.responseJSON.data)
            }
        });
    }

}

var Select = function (element, option, dataSrc) {
    this.element = document.getElementById(element),
        this.dataSrc = dataSrc,
        this.hasOptions = false
    this.option = option
    this.dataSet = [];

    const dataSetUpdated = new CustomEvent('data_set_update', { detail: { should_affect: this.element } })

    this.set_data = function (response) {
        if (this.dataSet.length != response.data.length) {
            this.dataSet = response.data
            dispatchEvent(dataSetUpdated)
        }
        //   do not trigger any rerendering
    }

    this.createOptions = function () {
        if (this.dataSet.length == 0) {
            this.hasOptions = false
            let option = document.createElement("option")
            option.selected = true;
            option.text = "No Data";
            this.element.appendChild(option)
        } else {
            // creates options into a select element 

            this.hasOptions = true
            let placeHolder = document.createElement('option')
            placeHolder.textContent = this.option.placeHolder ? this.option.placeHolder : '--Choose--'
            placeHolder.selected = true
            this.element.appendChild(placeHolder)
            this.dataSet.forEach((item) => {
                let option = document.createElement("option")
                option.text = item[this.option.key];
                option.value = item[this.option.value]
                this.element.appendChild(option)
            })
            return true;
        }
    }

    addEventListener('data_set_update', e => {
        while (this.element.firstChild) this.element.removeChild(this.element.firstChild)
        this.createOptions()
    })

    this.init = function () {
        this.fetch();
    }

    this.update = function () {

        this.init()
    }

    this.fetch = function () {

        $.ajax({
            method: 'GET',
            url: this.dataSrc.url,
            contentType: 'Json',
            headers: {
                'Authentication': this.dataSrc.token
            },
            cache: true,
            success: response => this.set_data(response),
            error: (xhr, status) => {
                $.notify('error', xhr.responseJSON.data.errors['info'])
                console.log(xhr.responseJSON.data)
            }
        });
    }
    // data is a rendering operation work out update means
    this.useDataSetToDoSomething = function (callback) {
        addEventListener('data_set_update', e => {
            callback(this.dataSet)
        })
    }
}



var Component = function (props) {

    this.element = document.createElement("div")

    this.element.classList = props.parentElementClassName ? props.parentElementClassName : '';

    this.element.id = props.id ? props.id : ''

    this.templateCreator = props.template

    this.data = props.data ? props.data : {}

    this.create = function () {

        this.element.innerHTML = this.templateCreator(this.data)

        return this.element;

    }

    this.update = function (newData = {}) {

        if (this.data != newData) {
            this.data = newData
            while (this.element.firstChild) this.element.removeChild(this.element.firstChild)
            return this.create()
        }
    }

}


var RemoveAllChildren = (el) => {
    var element = typeof el == "string" ? document.getElementById(el) : el
    while (element.firstChild) element.removeChild(element.firstChild)
    return;
}


var ApplicationForm = function ({ element, loader, messages, headers, callbacks }) {

    this.form = document.getElementById(element)
    this.prefix = element
    this.feedbackSection = document.getElementById(this.prefix + '_feedback')
    this.messages = messages
    this.callbacks = callbacks
    this.showLoader = function () {
        this.feedbackSection.innerHTML = typeof loader == "function" ? loader() : loader
    }
    this.form.addEventListener('submit', (e) => {
        this.action = this.form.getAttribute("action")
        this.method = this.form.getAttribute("method") ? this.form.getAttribute("method") : 'POST'
        e.preventDefault()
        this.showLoader()
        let dataObject = new FormData(this.form)
        $.ajax({
            method: this.method,
            url: this.action,
            processData: false,
            contentType: false,
            dataType: 'json',
            headers,
            data: dataObject,
            success: (response) => {
                RemoveAllChildren(this.feedbackSection)
                this.form.reset()
                document.querySelectorAll('.error-feedback').forEach((node) => { node.innerHTML = '' })
                if (this.messages.success.expectedStatus == response.status) {
                    this.feedbackSection.innerHTML = this.messages.success.text
                    this.callbacks.success(response.data)
                } else {
                    $.notify(`<div class="px-2 rounded-0 fade">un expected result from previous operation</div>`, 'info', "center")
                }
            },
            error: (xhr, status, error) => {

                RemoveAllChildren(this.feedbackSection)

                document.querySelectorAll('.error-feedback').forEach((node) => {
                    node.innerHTML = ''
                })
                if (xhr.status == 200) {
                    let errors = {}
                } else {
                    let errors = xhr.responseJSON.data.errors

                }
                let errors = xhr.responseJSON.data.errors

                let error_keys = Object.keys(errors)

                if (xhr.status == 422) {
                    this.feedbackSection.innerHTML = `<p class="p-2 text-danger">Check Some of those fields </p>`

                    error_keys.forEach((key) => {

                        // corresponds to the named field

                        if (key == 'info') {
                            let p = document.createElement("p")
                            p.classList = "p-2 text-warning"
                            p.textContent = error[key]
                            this.feedbackSection.appendChild(p)
                        } else if (key == 'error') {
                            $.notify(`<div class="px-2 rounded-0 fade">${error[key]}</div>`, 'info', "center")
                        } else {
                            document.getElementById(key + '_error').innerHTML = `<p class="p-1">${errors[key]}</p>`
                        }
                    })
                } else {
                    this.feedbackSection.innerHTML = `<p class="text-danger p-2">${this.messages.failure}</p>`
                }

                if (xhr.status == 401) {
                    document.forms['logout'].submit()
                }

                if (xhr.status == 403) {
                    $.notify(`<div class="px-2 rounded-0 fade">You do not have the Permission to perform this operation</div>`, 'info', "center")
                }

                if (xhr.status == 404) {
                    let p = document.createElement("p")
                    p.classList = "p-2 text-warning"
                    p.textContent = error.info
                    this.feedbackSection.appendChild(p)
                }

                if (xhr.status >= 500) {
                    $.notify(`<div class="px-2 rounded-0 fade">Server Error contact developer <a href="mailto:wanderatimothy2@gmail.com">Send</a> </div>`, 'info', "center")
                }

                this.callbacks.error(error)
            }

        })

    })

}

DeleteAction = function ({ action, delete_form, message = '' }) {
    let form = document.getElementById(delete_form)
    form.setAttribute('action', action)
}


 var customFieldCreator =  function({triggerElement,containerElement}){
     this.trigger = document.getElementById(triggerElement)
     this.container = document.getElementById(containerElement)
     this.container.appendChild(new Component({
         template:(data)=>{
             return  `<input name="has_custom_fields" value="1" type="number" min="0" max="1" class="d-none"/>`
         }
     }).create())
     this.items = 0;
     this.trigger.addEventListener('click',(event) => {
         event.preventDefault();
         let name = event.target.getAttribute('data-target-form');
         this.items += 1;
         let id = `${name}_custom_field_creation_section_${this.items}`
         let component = new Component({
            id : id,
            parentElementClassName: 'row p-1 align-items-end',
            template:(data)=>{
                return `<div class="col-md-3 col-lg-3 col-sm-12 form-group">
                            <label class="form-label" for="field name">Name</label>
                            <input type="text" name="custom_field_name[]" class="form-control" required minLength="2" maxLength="50" />
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 form-group">
                            <label class="form-label" for="field type">Type</label>
                            <select name="custom_field_type[]" id="${id}_type_select" class="form-control form-select" required>
                                <option value="" selected >Choose</option>
                                <option value="Text">Text</option>
                                <option value="Phone">Phone</option>
                                <option value="Email">Email</option>
                                <option value="Number">Number</option>
                                <option value="Date">Date</option>
                                <option value="Description">Description</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-lg-3 col-sm-12 form-group">
                             <label class="form-label" for="field Value">Value</label>
                             <div id="${id}_value_area"></div>
                        </div>
                        <button type="button" id="${id}_delete_section" class="btn btn-danger btn-sm col-md-2 col-lg-2 col-sm-12 ms-1" >Delete</button>
                        `
            },
            
         })

         this.container.appendChild(component.create())
         document.getElementById(`${id}_delete_section`).addEventListener('click',event=>{
             document.getElementById(id).remove()
         })
         document.getElementById(`${id}_type_select`).addEventListener('change',event=>{
             let value = event.target.value;
             let value_area = document.getElementById(`${id}_value_area`)
             RemoveAllChildren(value_area)
             switch(value){
                 case 'Text':
                     value_area.appendChild(new Component({
                        template : (data) => {
                            return `<input type="text" class="form-control" required minLength="1" maxLength="150" name="custom_field_value[]" />`
                        }
                     }).create())
                 break;
                 case 'Phone':
                     value_area.appendChild(new Component({
                        template : (data) => {
                            return `<input type="text" class="form-control" required minLength="10" placeholder="PhoneNumber" maxLength="14" name="custom_field_value[]" />`
                        }
                     }).create())
                 break;
                 case 'Number':
                     value_area.appendChild(new Component({
                        template : (data) => {
                            return `<input type="number" class="form-control" required placeholder="Number"  name="custom_field_value[]" />`
                        }
                     }).create())
                 break;
                 case 'Email':
                     value_area.appendChild(new Component({
                        template : (data) => {
                            return `<input type="email" class="form-control" placeholder="some_email@email.com" required minLength="1" maxLength="150" name="custom_field_value[]" />`
                        }
                     }).create())
                 break;
                 case 'Description':
                     value_area.appendChild(new Component({
                        template : (data) => {
                            return `<textarea name="custom_field_value[]" class="form-control" required placeholder="Some text saying ..." rows="3"></textarea>`
                        }
                     }).create())
                 break;
                 case 'Date':
                     value_area.appendChild(new Component({
                        template : (data) => {
                            return `<input type="date" class="form-control" required minLength="1" maxLength="150" name="custom_field_value[]" />`
                        }
                     }).create())
                 break;
                
                default:
                     value_area.appendChild(new Component({
                        template : (data) => {
                            return `<input type="text" class="form-control" required minLength="1" maxLength="150" name="custom_field_value[]" />`
                        }
                     }).create())
             }
         })
     })
     
 }
        


