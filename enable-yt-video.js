window.wp.customHeader.__proto__.supportsVideo = function() {
	return true;
}

window.wp.customHeader.handlers.youtube.__proto__.loadVideo = function() {
	var handler = this,
		video = document.createElement( 'div' ),
		// @link http://stackoverflow.com/a/27728417
		VIDEO_ID_REGEX = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;

	video.id = 'wp-custom-header-video';
	handler.setVideo( video );

	if ( ! 'YT' in window ) {
		return false;
	}
	
	handler.player = new YT.Player( video, {
		height: this.settings.height,
		width: this.settings.width,
		videoId: this.settings.videoUrl.match( VIDEO_ID_REGEX )[1],
		events: {
			onReady: function( e ) {
				e.target.mute();
				e.target.playVideo();
			},
			onStateChange: function( e ) {
				if ( YT.PlayerState.PLAYING === e.data ) {
					handler.trigger( 'play' );
				} else if ( YT.PlayerState.PAUSED === e.data ) {
					handler.trigger( 'pause' );
				} else if ( YT.PlayerState.ENDED === e.data ) {
					e.target.playVideo();
				}
			}
		},
		playerVars: {
			autoplay: 1,
			controls: 0,
			disablekb: 1,
			fs: 0,
			iv_load_policy: 3,
			loop: 1,
			modestbranding: 1,
			playsinline: 1,
			rel: 0,
			showinfo: 0
		}
	});
};