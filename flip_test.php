<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://cdn.rawgit.com/nnattawat/flip/master/dist/jquery.flip.min.js"></script>



<style>
.answer{
	display: flex;
	justify-content: center;
	align-items: center;
	border-radius: 10px;

	width: 75px;
	height: 98px;
}

</style>
<div style = 'height: 150px; width: auto;'>
	<div class = 'card'>
		<div class = 'front'>
			<img src = '_stdlib/_images/_icons/card_back.png' />
		</div>
		<div class = 'back'>
			<div class='answer'>
					The answer
			</div>
		</div>
	</div>
</div>

<script>



		$('.card').flip({
			'toggle': 'click',
			'axis' : 'y'
		});


</script>