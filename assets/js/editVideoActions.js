function setNewThumbnail(thumbnailId, videoId, itemElement) {
	$.post('ajax/updateThumbnail.php', {
		videoId: videoId,
		thumbnailId: thumbnailId
	}).done(function() {
		var item = $(itemElement);
		var itemClass = item.attr('class');

		$('.' + itemClass).removeClass('selected');
		item.addClass('selected');

		if (!$('#videoContainer').find('.alert').length) {
			$('#videoContainer').prepend(
				`<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>SUCESS!</strong> Thumbnail updated successfully.
  					<button type = "button" class= "close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button >
				</div >`
			);
		}
	});
}
