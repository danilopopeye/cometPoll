(function(){
	window.CPoll= {
		url: '/broadcast/sub?channel=',

		init: function(){
			$('poll-form').addEvent('submit', this.submit);

			this.request();

			console.info('CPoll initialized', this.channel);
		},
		submit: function(e){
			e.stop();

			new Request.JSON({
				url: this.get('action'),
				data: this,
				onComplete: function(json){
					var isOk = json.status,
						message = isOk ? 'success' : 'error';

					$('poll-response').set({
						class: message, html: message
					});
				}
			}).send();
		},
		request: function(){
			var self = this;
			new Request.JSON({
				url: this.url + this.channel,
				method: 'GET',
				onSuccess: function(json){
					// get the server modified header
					self.lastModified = this.getHeader('Last-Modified');

					console.info( self.lastModified, json );

					$('poll-response').set('html', json.message);

					setTimeout( self.request.bind(self), 100 );
				}
			})

			// set the modified header
			.setHeader('If-Modified-Since',self.lastModified)

			// send the request
			.send();
		}
	};

	// find $.proxy like function for here
	window.addEvent('domready', CPoll.init.bind( CPoll ));
})();
