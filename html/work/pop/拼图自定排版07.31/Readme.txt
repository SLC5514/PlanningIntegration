flash 中交互js 定义：

1、从图库上传图片 -- Flash 调用网页端
function puzzleFromPhotoUpload( oSelected ) { 
	//弹层获取图库列表
	/*
	oSelected 当前选择文件列表可为空json对象:
		[
			"product_perform_1",
			"product_perform_2",
			"product_perform_3",
			...
		]
	*/
	
	//请求弹层，展示check 无

}

	
2、从图库上传图片，确定后触发事件 -- 网页端 调 Flash方法( 第三方 Flash 提供 )
传递参数值结构：
[
	{
		"ID":"product_perform_1",
		"bigpic":"http://img2.fm.pop-fashion.com/fashion/designreference/graphic/20161202002/child/OthersEur/2018SS/fabric/Mostrend/detail/images/c3ea8d58c4481ce79b81859a825561ed.jpg",
		"smallpic":"http://img2.fm.pop-fashion.com/fashion/designreference/graphic/20161202002/child/OthersEur/2018SS/fabric/Mostrend/detail/thumbnails/001.jpg"
	},
	{
		"ID":"product_perform_2",
		"bigpic":"http://img2.fm.pop-fashion.com/fashion/designreference/graphic/20161202002/child/OthersEur/2018SS/fabric/Mostrend/detail/images/c3ea8d58c4481ce79b81859a825561ed.jpg",
		"smallpic":"http://img2.fm.pop-fashion.com/fashion/designreference/graphic/20161202002/child/OthersEur/2018SS/fabric/Mostrend/detail/thumbnails/001.jpg"
	}
]
	

3、

