
var Table = function(elm , dataSrc , headers , columns){
    this.elm = elm
    this.src = dataSrc
    this.columns = columns
    this.data = []
    this.headers = headers
    this.create = function(){
        $.ajax({
            method:'GET',
            url:this.src,
            contentType:'json',
            headers:this.headers,
            success:function(res){
                this.url
            }
        })
    }
    this.response;
}



