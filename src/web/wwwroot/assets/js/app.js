   var Table = function(columns , $element , dataSrc , callback = null){

    this.columns = columns

    this.element = document.getElementById($element);

    this.url = dataSrc.url;

    this.token = dataSrc.token;
    
    this.name = $element

    this.dataCount = 0

    this.callback = callback

    this.countElement =  document.getElementById(this.name + '-counter')


    this.creatHeadings = function(){

        this.element.classList = "table align-items-center text-center"
        var thead = document.createElement('thead')
        var tr = document.createElement("tr")
        this.columns.forEach(element => {
            let th = document.createElement('th')
            th.textContent = element.title
            th.classList = "text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"
            tr.appendChild(th)
        });
        thead.appendChild(tr)
        this.element.appendChild(thead)

    }


    this.creatHeadings();

    this.updateBody = function(response){
        var tbody = document.createElement("tbody")
        tbody.id = this.name + '-tbody-section';
        var data = response.data
        if(data.length == 0){
            let row = document.createElement("tr")
            let td = document.createElement("td")
            td.setAttribute("colspan",this.columns.length)
            td.classList = "text-capitalize py-2 text-muted";
            td.textContent = "No Items to Show"
            row.appendChild(td)
            tbody.appendChild(row)
        }else{
            this.dataCount = data.length
            data.forEach((row , $index) => {

                let tr = document.createElement("tr")

                this.columns.forEach((item)=>{

                    let td = document.createElement('td')
                    td.innerHTML = typeof item.data == "function" ? item.data(row,$index) : row[item.name]
                    tr.appendChild(td)
                })

                tbody.appendChild(tr)
                this.countElement.innerHTML = `${1 + $index}`
            })            
        }
        this.element.appendChild(tbody)

        if(typeof this.callback != null) {
            this.callback()
        }

        return true;
    }   


    this.init = function(){
        this.fetch();
    }
    this.update = function(){

        this.element.removeChild(document.getElementById(this.name + '-tbody-section'))
        this.fetch();
    }

    this.fetch = function(){

        $.ajax({
            method:'GET',
            url:this.url,
            contentType:'Json',
            cache:true,
            headers:{
              'Authentication':this.token
            },
            success:response=>this.updateBody(response),
            error:(xhr,status)=>{
              $.notify('error', xhr.responseJSON.data.errors['info'])
              console.log(xhr.responseJSON.data)
            }
          });
    }
    
}

var Select = function(element ,option, dataSrc){
    this.element = document.getElementById(element),
    this.dataSrc = dataSrc,
    this.hasOptions = false
    this.option = option
    this.dataSet = [];
    
    const dataSetUpdated = new CustomEvent('data_set_update',{detail:{ should_affect : this.element }})
   
    this.set_data = function(response){
      if(this.dataSet.length != response.data.length){
          this.dataSet = response.data
          dispatchEvent(dataSetUpdated)
      }
    //   do not trigger any rerendering
    }
        
    this.createOptions = function(){
        if(this.dataSet.length == 0 ){
            this.hasOptions = false
            let option = document.createElement("option")
            option.selected = true;
            option.text = "No Data";
            this.element.appendChild(option)
        }else{
            // creates options into a select element 
            
            this.hasOptions = true
            let placeHolder = document.createElement('option')
            placeHolder.textContent = this.option.placeHolder ? this.option.placeHolder : '--Choose--'
            placeHolder.selected = true
            this.element.appendChild(placeHolder)
            this.dataSet.forEach((item)=>{
                let option = document.createElement("option")
                option.text = item[this.option.key];
                option.value = item[this.option.value]
                this.element.appendChild(option)
            })
            return true;
        }
    }

    addEventListener('data_set_update',e=>{
        while(this.element.firstChild) this.element.removeChild(this.element.firstChild)
        this.createOptions()
    })

    this.init = function(){
        this.fetch();
    }

    this.update = function(){
   
        this.init()
    }
    
    this.fetch = function(){

        $.ajax({
            method:'GET',
            url:this.dataSrc.url,
            contentType:'Json',
            headers:{
              'Authentication':this.dataSrc.token
            },
            cache:true,
            success:response=>this.set_data(response),
            error:(xhr,status)=>{
              $.notify('error', xhr.responseJSON.data.errors['info'])
              console.log(xhr.responseJSON.data)
            }
          });
    }
    // data is a rendering operation work out update means
    this.useDataSetToDoSomething = function(callback){
        addEventListener('data_set_update',e=>{
            callback(this.dataSet)
        })
    }
}



var Component = function(props){

    this.element = document.createElement("div")

    this.templateCreator = props.template

    this.data = props.data ? props.data : {}

    this.create = function () {
        
    this.element.innerHTML = this.templateCreator(this.data)

    return this.element;

    }

    this.update = function (newData = {}) {

        if(this.data != newData){
            this.data = newData
            while(this.element.firstChild) this.element.removeChild(this.element.firstChild)
             return  this.create()
        }
    }

}


var RemoveAllChildren = (el)=>{
    var element = typeof el == "string" ? document.getElementById(el) : el
    while(element.firstChild)element.removeChild(element.firstChild)
    return;
}


var ApplicationForm = function({element ,loader,messages ,headers,callbacks}){

    this.form = document.getElementById(element)
    this.prefix = element
    this.feedbackSection = document.getElementById(this.prefix + '_feedback')
    this.messages = messages
    this.callbacks = callbacks
    this.showLoader = function(){
        this.feedbackSection.innerHTML = typeof loader == "function" ? loader() : loader
    }
    this.form.addEventListener('submit',(e)=>{
        this.action = this.form.getAttribute("action")
        this.method = this.form.getAttribute("method") ? this.form.getAttribute("method") : 'POST'
        e.preventDefault()
        this.showLoader()
        let dataObject = new FormData(this.form)
        $.ajax({
            method:this.method,
            url:this.action,
            processData: false,
            contentType:false,
            dataType:'json',
            headers,
            data:dataObject,
            success:(response)=>{
              RemoveAllChildren(this.feedbackSection)
              this.form.reset()
              document.querySelectorAll('.error-feedback').forEach((node)=>{node.innerHTML = ''})
              if(this.messages.success.expectedStatus == response.status){
                this.feedbackSection.innerHTML = this.messages.success.text
                this.callbacks.success(response.data)
              }else{
                  $.notify(`<div class="px-2 rounded-0 fade">un expected result from previous operation</div>`,'info',"center")
              }
            },
            error:(xhr,status,error) => {

                RemoveAllChildren(this.feedbackSection)

                document.querySelectorAll('.error-feedback').forEach((node)=>{
                    node.innerHTML = ''
                })
                if(xhr.status == 200){
                    let errors = {}
                }else{
                    let errors = xhr.responseJSON.data.errors

                }
                let errors = xhr.responseJSON.data.errors

                let error_keys = Object.keys(errors)

                if(xhr.status == 422){
                    this.feedbackSection.innerHTML = `<p class="p-2 text-danger">Check Some of those fields </p>`
                
                    error_keys.forEach((key)=>{
                        
                        // corresponds to the named field

                        if(key == 'info'){
                            let p = document.createElement("p")
                            p.classList ="p-2 text-warning"
                            p.textContent = error[key]
                            this.feedbackSection.appendChild(p)
                        }else if(key == 'error'){
                            $.notify(`<div class="px-2 rounded-0 fade">${error[key]}</div>`,'info',"center")
                        }else{
                            document.getElementById(key+'_error').innerHTML = `<p class="p-1">${errors[key]}</p>`
                        }
                    })
                }else{
                    this.feedbackSection.innerHTML = `<p class="text-danger p-2">${this.messages.failure}</p>`
                }

                if(xhr.status  == 401){
                    document.forms['logout'].submit()
                }

                if(xhr.status == 403){
                    $.notify(`<div class="px-2 rounded-0 fade">You do not have the Permission to perform this operation</div>`,'info',"center") 
                }

                if(xhr.status == 404){
                    let p = document.createElement("p")
                    p.classList ="p-2 text-warning"
                    p.textContent = error.info
                    this.feedbackSection.appendChild(p)     
               }

               if(xhr.status >= 500){
                 $.notify(`<div class="px-2 rounded-0 fade">Server Error contact developer <a href="mailto:wanderatimothy2@gmail.com">Send</a> </div>`,'info',"center") 
               }

               this.callbacks.error(error)
            }

        })

    })
    
}

DeleteAction = function ({action , delete_form , message = ''}){
    let form = document.getElementById(delete_form)
    form.setAttribute('action', action)
}
