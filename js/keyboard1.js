(function(exports){
  var KeyBoard = function(input, options){
    var body = document.getElementsByTagName('body')[0];
    var DIV_ID = options && options.divId || 'out_div';
     
    if(document.getElementById(DIV_ID)){
      body.removeChild(document.getElementById(DIV_ID));
    }
     
    this.input = input;
    this.el = document.createElement('div');
     
    var self = this;
    var zIndex = options && options.zIndex || 1000;
    var width = options && options.width || '500px';
    var height = options && options.height || '350px';
    var fontSize = options && options.fontSize || '25px';
    var backgroundColor = options && options.backgroundColor || 'rgba(255,255,255,0.3)';
    var TABLE_ID = options && options.table_id || 'table_in';
  //  var left= options.left-590;
   // var top = options.top-83;
    var left=450;
    var top =175;      
    console.log(options);
    var mobile = typeof orientation !== 'undefined';
    this.el.id = DIV_ID;
  //  this.el.style.position = 'absolute';
    this.el.style.position = 'fixed';
    this.el.style.top = top+"px";
    this.el.style.left = left+"px";
    this.el.style.right = 0;
    this.el.style.bottom = 0;
    this.el.style.zIndex = zIndex;
    this.el.style.width = width;
    this.el.style.height = height;
    this.el.style.backgroundColor = backgroundColor;
     
    //样式
    var cssStr = '<style type="text/css">';
    cssStr += '#' + TABLE_ID + '{text-align:center;width:100%;height:300px;font-size:50px;font-weight:800;border-top:1px solid #CECDCE;background-color:rgba(255,255,255,0.3);}';
    cssStr += '#' + TABLE_ID + ' td{width:33%;height:25%;border:1px solid #ddd;border-right:0;border-top:0;}';
    if(!mobile){
      cssStr += '#' + TABLE_ID + ' td:hover{background-color:#1FB9FF;color:#FFF;}';
    }
    cssStr += '</style>';
     
    //Button
    var btnStr = '<div style="width:32%;height:22%;font-size:35px;padding-top:20px;background-color:rgba(122,222,255,0.3););';
    btnStr += 'float:right;margin-right:5px;text-align:center;color:#fff;';
    btnStr += 'line-height:28px;border-radius:3px;margin-bottom:5px;cursor:pointer;">关闭</div>';
     
    //table
    var tableStr = '<table id="' + TABLE_ID + '" border="0" cellspacing="0" cellpadding="0">';
      tableStr += '<tr><td>1</td><td>2</td><td>3</td></tr>';
      tableStr += '<tr><td>4</td><td>5</td><td>6</td></tr>';
      tableStr += '<tr><td>7</td><td>8</td><td>9</td></tr>';
      tableStr += '<tr><td style="background-color:rgba(255,255,255,0.3);">.</td><td>0</td>';
      tableStr += '<td style="background-color:rgba(255,255,255,0.3);">删除</td></tr>';
      tableStr += '</table>';
    this.el.innerHTML = cssStr + btnStr + tableStr;
     
    function addEvent(e){
      var ev = e || window.event;
      var clickEl = ev.element || ev.target;
      var value = clickEl.textContent || clickEl.innerText;
      if(clickEl.tagName.toLocaleLowerCase() === 'td' && value !== "删除"){
        if(self.input){
          self.input.value += value;
        }
      }else if(clickEl.tagName.toLocaleLowerCase() === 'div' && value === "关闭"){
        body.removeChild(self.el);
      }else if(clickEl.tagName.toLocaleLowerCase() === 'td' && value === "删除"){
        var num = self.input.value;
        if(num){
          var newNum = num.substr(0, num.length - 1);
          self.input.value = newNum;
        }
      }
    }
     
    if(mobile){
      this.el.ontouchstart = addEvent;
    }else{
      this.el.onclick = addEvent;
    }
    body.appendChild(this.el);
  }
   
  exports.KeyBoard = KeyBoard;
 
})(window);

