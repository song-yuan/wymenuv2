wx.ready(function () {
	wx.showMenuItems({
      menuList: [
        'menuItem:refresh'//刷新
      ],
      success: function (res) {
      },
      fail: function (res) {
      }
    });

    //分享到朋友圈
    wx.onMenuShareTimeline({
      title: title, // 分享标题
      link: link, // 分享链接
      imgUrl: imgUrl, // 分享图标
      success: function () { 
          // 用户确认分享后执行的回调函数
            var type = $('#wx_share_redEven').val();
	        var brandId = $('#brand_id').val();
	        var userId = $('#user_id').val();
	        if(parseInt(type)==0||parseInt(type)==1||parseInt(type)==2){
	        	$.ajax({
	        		url:'index.php?r=member/weixin/wxRed',
	        		data:{brandId:brandId,userId:userId,type:type},
	        		success:function(data){
	        		},
	        		dataType:'json',
	        	});
	        }
      },
      cancel: function () { 
          // 用户取消分享后执行的回调函数
      }
	});
	//分享到朋友
	wx.onMenuShareAppMessage({
	    title: title, // 分享标题
	    desc: desc, // 分享描述
	    link: link, // 分享链接
	    imgUrl: imgUrl, // 分享图标
	    type: '', // 分享类型,music、video或link，不填默认为link
	    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	    success: function () { 
	        // 用户确认分享后执行的回调函数
	       
	    },
	    cancel: function () { 
	        // 用户取消分享后执行的回调函数
	    }
	});
    $('.nearby').click(function(){
        wx.getLocation({
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                $.ajax({
                    url:'index.php?r=market/brands/Location',
                    type:'post',
                    async: false,
                    //contentType: "application/x-www-form-urlencoded; charset=utf-8",
                    data: {
                        latitude:latitude,
                        longitude:longitude,
                        speed:speed,
                        accuracy:accuracy
                    },
                    success: function(msg) {
                        //如果返回1，则认为添加成功，展现下面弹出层；否则不显示弹出层

                    }
                });//ajax end
            }
        });
    });

	//晒单分享
	 $('.bttn_share').click(function(){
    	//分享到朋友圈
	    wx.onMenuShareTimeline({
	      title: title, // 分享标题
	      link: link, // 分享链接
	      imgUrl: imgUrl, // 分享图标
	      success: function () { 
	          // 用户确认分享后执行的回调函数
	      },
	      cancel: function () { 
	          // 用户取消分享后执行的回调函数
	      }
		});
     });
        
    wx.error(function(res){

    // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
	
	});
});