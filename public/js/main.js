$(document).ready(()=>{
	const currentUrl = window.location.href
	const options = ['products','categories','contact']

	options.forEach(item => {
		if(currentUrl.indexOf(item)!==-1){
			$(`#${item}`).addClass('active')
		}
	})
})