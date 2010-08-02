(function(){
	window.CPoll= {
		url: '/broadcast/sub?channel=',
		init: function(){
			$('poll-form').addEvent('submit', this.submit);

			this.request();

			this.build();

			console.info('CPoll initialized on channel: ', this.channel);
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
			var self = this, data = this.data;
			new Request.JSON({
				url: this.url + this.channel,
				method: 'GET',
				onSuccess: function(json){
					// get the server modified header
					var last = this.getHeader('Last-Modified');

					self.lastModified = last != self.lastModified
						? last : new Date( +Date.parse( last ) + 1000 ).toGMTString();

					if( $type( json.vote ) == 'string' ){
						// TODO: refactor this, it's ugly :(
						data.total = ++data.total;
						data.choices[ json.vote ].votes = ++data.choices[ json.vote ].votes;

						self.build();
					}

					setTimeout( self.request.bind(self), 100 );
				}
			})

			// set the modified header
			.setHeader('If-Modified-Since',self.lastModified)

			// send the request
			.send();
		},
		build: function(first){
			var self = this, total = this.data.total;

			$each(this.data.choices, function(c, i){
				var id = c.id;
				$('choice-' + id).getElement('b').set('html', c.votes);
				$('bar-' + id).tween('width', c.votes * 400 / total );
			});
		}
	};

	// find $.proxy like function for here
	window.addEvent('domready', CPoll.init.bind( CPoll ));
})();
