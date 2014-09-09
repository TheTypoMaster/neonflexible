/* This file is automatically generated */

/*

Copyright (c) 2008 Rizqi Ahmad

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/

/* core.js */
(function($){

$.Chain =
{
	version: '0.1.9a',

	tag: ['{', '}'],

	services: {},

	service: function(name, proto)
	{
		this.services[name] = proto;

		$.fn[name] = function(options)
		{
			if(!this.length) return this;

			var instance = this.data('chain-'+name);
			var args = Array.prototype.slice.call(arguments, 1);

			if(!instance)
			{
				instance = $.extend({element: this}, $.Chain.services[name]);
				this.data('chain-'+name, instance);
				if(instance.init)
					instance.init();
			}

			var result;

			if(typeof options == 'string' && instance['$'+options])
				result = instance['$'+options].apply(instance, args);
			else if(instance['handler'])
				result = instance['handler'].apply(instance, [options].concat(args));
			else
				result = this;

			if(options == 'destroy')
				this.removeData('chain-'+name);

			return result;
		};
	},

	extend: function(name, proto)
	{
		if(this.services[name])
			this.services[name] = $.extend(this.services[name], proto);
	},

	jobject: function(obj)
	{
		return obj && obj.init == $.fn.init;
	},

	jidentic: function(j1, j2)
	{
		if(!j1 || !j2 || j1.length != j2.length)
			return false;

		a1 = j1.get();
		a2 = j2.get();

		for(var i=0; i<a1.length; i++)
			if(a1[i] != a2[i])
				return false;

		return true;

	},

	parse: (function()
	{
		var $this = {};
		// Function Closure
		$this.closure =
		[
			'function($data, $el){'
			+'var $text = [];\n'
			+'$text.print = function(text)'
			+'{this.push((typeof text == "number") ? text : ((typeof text != "undefined") ? text : ""));};\n'
			+'with($data){\n',

			'}\n'
			+'return $text.join("");'
			+'}'
		];

		// Print text template
		$this.textPrint = function(text)
		{
			return '$text.print("'
				+text.split('\\').join('\\\\').split("'").join("\\'").split('"').join('\\"')
				+'");';
		};

		// Print script template
		$this.scriptPrint = function(text)
		{
			return '$text.print('+text+');';
		};

		$this.parser = function(text){
			var tag = $.Chain.tag;

			var opener, closer, closer2 = null, result = [];

			while(text){

				// Check where the opener and closer tag
				// are located in the text.
				opener = text.indexOf(tag[0]);
				closer = opener + text.substring(opener).indexOf(tag[1]);

				// If opener tag exists, otherwise there are no tags anymore
				if(opener != -1){
					// Handle escape. Tag can be escaped with '\\'.
					// If tag is escaped. it will be handled as a normal text
					// Otherwise it will be handled as a script
					if(text[opener-1] == '\\'){
						closer2 = opener+tag[0].length + text.substring(opener+tag[0].length).indexOf(tag[0]);
						if(closer2 != opener+tag[0].length-1 && text[closer2-1] == '\\')
							closer2 = closer2-1;
						else if(closer2 == opener+tag[0].length-1)
							closer2 = text.length;

						result.push($this.textPrint(text.substring(0, opener-1)));
						result.push($this.textPrint(text.substring(opener, closer2)));
					}
					else{
						closer2 = null;
						if(closer == opener-1)
							closer = text.length;

						result.push($this.textPrint(text.substring(0, opener)));
						result.push($this.scriptPrint(text.substring(opener+tag[0].length, closer)));
					}

					text = text.substring((closer2 == null) ? closer+tag[1].length : closer2);
				}
				// If there are still text, it will be pushed to array
				// So we won't stuck in an infinite loop
				else if(text){
					result.push($this.textPrint(text));
					text = '';
				}
			}

			return result.join('\n');
		}


		/*
		 * Real function begins here.
		 * We use closure for private variables and function.
		 */
		return function($text)
		{
			var $fn;
			try
			{
				eval('$fn = '+ $this.closure[0]+$this.parser($text)+$this.closure[1]);
			}
			catch(e)
			{
				throw "Parsing Error";
				$fn = function(){};
			}

			return $fn;
		};
	})()
};

})(jQuery);


/* update.js */
(function($){

$.Chain.service('update', {
	handler: function(opt)
	{
		if(typeof opt == 'function')
			return this.bind(opt);
		else
			return this.trigger(opt);
	},

	bind: function(fn)
	{
		return this.element.bind('update', fn);
	},

	trigger: function(opt)
	{
		this.element.items('update');
		this.element.item('update');

		this.element.triggerHandler('preupdate', this.element.item());

		if(opt == 'hard')
			this.element.items(true).each(function(){$(this).update();});

		this.element.triggerHandler('update', this.element.item());

		return this.element;
	}
});

})(jQuery);


/* chain.js */
(function($){

$.Chain.service('chain', {
	init: function()
	{
		this.anchor = this.element;
		this.template = this.anchor.html();
		this.tplNumber = 0;
		this.builder = this.createBuilder();
		this.plugins = {};
		this.isActive = false;
		this.destroyers = [];

		this.element.addClass('chain-element');
	},

	handler: function(obj)
	{
		this.element.items('backup');
		this.element.item('backup');

		if(typeof obj == 'object')
			this.build(obj);
		else
			if(typeof obj == 'function')
				this.builder = this.createBuilder(obj);

		this.anchor.empty();

		this.isActive = true;
		this.element.update();

		return this.element;
	},

	build: function(rules)
	{
		var builder = rules.builder;
		delete rules.builder;

		if(rules.anchor)
			this.setAnchor(rules.anchor);
		delete rules.anchor;

		var override = rules.override;
		delete rules.override;

		for(var i in rules)
		{
			if(typeof rules[i] == 'string')
			{
				rules[i] = $.Chain.parse(rules[i]);
			}
			else if(typeof rules[i] == 'object')
			{
				for(var j in rules[i])
				{
					if(typeof rules[i][j] == 'string')
						rules[i][j] = $.Chain.parse(rules[i][j]);
				}
			}
		}

		var fn = function(event, data)
		{
			var el, val;
			var self = $(this);
			for(var i in rules)
			{
				el = $(i, self);

				if (typeof rules[i] == 'function')
				{
					val = rules[i].apply(self, [data, el]);
					if(typeof val == 'string')
						el.not(':input').html(val).end().filter(':input').val(val);
				}
				else if(typeof rules[i] == 'object')
				{
					for(var j in rules[i])
					{
						if (typeof rules[i][j] == 'function')
						{
							val = rules[i][j].apply(self, [data, el]);
							if(typeof val == 'string')
							{
								if(j == 'content')
									el.html(val);
								else if(j == 'text')
									el.text(val);
								else if(j == 'value')
									el.val(val);
								else if(j == 'class' || j == 'className')
									el.addClass(val);
								else
									el.attr(j, val);
							}

						}
					}
				}
			}
		};

		var defBuilder = this.defaultBuilder;

		this.builder = function(root)
		{
			if(builder)
				builder.apply(this, [root]);

			if(!override)
				defBuilder.apply(this);

			this.update(fn);

			return false;
		};
	},

	// Builder, not executable
	defaultBuilder: function(builder, root)
	{
		var res = builder ? (builder.apply(this, [root]) !== false) : true;

		if(res)
			this.update(function(event, data){
				var self = $(this);
				for(var i in data)
					if(typeof data[i] != 'object' && typeof data[i] != 'function')
						self.find('> .'+i+', *:not(.chain-element) .'+i)
							.each(function(){
								var match = $(this);
								if(match.filter(':input').length)
									match.val(data[i]);
								else if(match.filter('img').length)
									match.attr('src', data[i]);
								else
									match.html(data[i]);
							});
			});
	},

	createBuilder: function(builder)
	{
		var defBuilder = this.defaultBuilder;
		return function(root){
			defBuilder.apply(this, [builder, root]);
			return false;
		};
	},

	setAnchor: function(anchor)
	{
		this.anchor.html(this.template);
		this.anchor = anchor == this.element ? anchor : this.element.find(anchor);
		this.template = this.anchor.html();
		this.anchor.empty();
	},

	$anchor: function(val)
	{
		if(val)
		{
			this.element.items('backup');
			this.element.item('backup');

			this.setAnchor(val);
			this.element.update();

			return this.element;
		}
		else
		{
			return this.anchor;
		}
	},

	$template: function(arg)
	{
		if(!arguments.length)
			return $('<div>').html(this.template).children().eq(this.tplNumber);

		if(arg == 'raw')
			return this.template;

		if(typeof arg == 'number')
		{
			this.tplNumber = arg;
		}
		else
		{
			var tpl = $('<div>').html(this.template).children();
			var node = tpl.filter(arg).eq(0);

			if(node.length)
				this.tplNumber = tpl.index(node);
			else
				return this.element;
		}

		this.element.items('backup');
		this.element.item('backup');
		this.element.update();

		return this.element;
	},

	$builder: function(builder)
	{
		if(builder)
			return this.handler(builder);
		else
			return this.builder;
	},

	$active: function()
	{
		return this.isActive;
	},

	$plugin: function(name, fn)
	{
		if(fn === null)
			delete this.plugins[name];
		else if(typeof fn == 'function')
			this.plugins[name] = fn;
		else if(name && !fn)
			return this.plugins[name];
		else
			return this.plugins;

		if(typeof fn == 'function')
			this.element.items(true).each(function(){
				var self = $(this);
				fn.call(self, self.item('root'));
			});

		this.element.update();

		return this.element;
	},

	$clone: function()
	{
		var id = this.element.attr('id');
		this.element.attr('id', '');

		var clone = this.element.clone().empty().html(this.template);
		this.element.attr('id', id);

		return clone;
	},

	$destroy: function(nofollow)
	{
		this.element.removeClass('chain-element');

		if(!nofollow)
		{
			this.element.items('backup');
			this.element.item('backup');

			this.element.find('.chain-element').each(function(){
				$(this).chain('destroy', true);
			});
		}

		this.element.triggerHandler('destroy');

		this.isActive = false;

		this.anchor.html(this.template);

		return this.element;
	}
});

})(jQuery);


/* items.js */
(function($){

$.Chain.service('items', {
	collections:
	{
		all: function()
		{
			return this.element.chain('anchor').children('.chain-item');
		},

		visible: function()
		{
			return this.element.chain('anchor').children('.chain-item:visible');
		},

		hidden: function()
		{
			return this.element.chain('anchor').children('.chain-item:hidden');
		},

		self: function()
		{
			return this.element;
		}
	},

	init: function()
	{
		this.isActive = false;
		this.pushBuffer = [];
		this.shiftBuffer = [];
		this.collections = $.extend({}, this.collections);
	},

	handler: function(obj)
	{
		if(obj instanceof Array)
			return this.$merge(obj);
		else if(!this.isActive)
			return $().eq(-1);
		else if($.Chain.jobject(obj))
			return (!$.Chain.jidentic(obj, obj.item('root')) && $.Chain.jidentic(this.element, obj.item('root')))
				? obj : $().eq(-1);
		else if(typeof obj == 'object')
			return this.getByData(obj);
		else if(typeof obj == 'number')
			return this.getByNumber(obj);
		else if(obj === true)
			return this.collection('all');
		else
			return this.collection('visible');
	},

	getByData: function(item)
	{
		return this.collection('all').filter(function(){return $(this).item() == item});
	},

	getByNumber: function(number)
	{
		if(number == -1)
			return this.collection('visible').filter(':last');
		else
			return this.collection('visible').eq(number);
	},

	update: function()
	{
		this.element.update();
	},

	empty: function()
	{
		var all = this.collection('all');

		// Make it run in the background. for responsiveness.
		setTimeout(function(){all.each(function(){$(this).item('remove', true)});}, 1);

		this.element.chain('anchor').empty();
	},

	collection: function(col, fn)
	{
		if(arguments.length > 1)
		{
			if(typeof fn == 'function')
				this.collections[col] = fn;

			return this.element;
		}
		else
		{
			if(this.collections[col])
				return this.collections[col].apply(this);
			else
				return $().eq(-1);
		}

	},

	$update: function()
	{
		if(!this.element.chain('active') || !this.isActive)
			return this.element;

		var self = this;
		var builder = this.element.chain('builder');
		var template = this.element.chain('template');
		var push;

		var iterator = function(){
			var clone = template
				.clone()[push ? 'appendTo' :'prependTo'](self.element.chain('anchor'))
				.addClass('chain-item')
				.item('root', self.element);

			if(self.linkElement && $.Chain.jobject(this) && this.item())
				clone.item('link', this, 'self');
			else
				clone.item(this);

			clone.chain(builder);
		};

		push = false;
		$.each(this.shiftBuffer, iterator);
		push = true;
		$.each(this.pushBuffer, iterator);


		this.shiftBuffer = [];
		this.pushBuffer = [];

		return this.element;
	},

	$push: function()
	{
		this.isActive = true;
		this.pushBuffer = this.pushBuffer.concat(Array.prototype.slice.call(arguments));
		this.update();

		return this.element;
	},

	$shift: function()
	{
		this.isActive = true;
		this.shiftBuffer = this.shiftBuffer.concat(Array.prototype.slice.call(arguments));
		this.update();

		return this.element;
	},

	$add: function()
	{
		var cmd;
		var args = Array.prototype.slice.call(arguments);
		if(typeof args[0] == 'string')
			cmd = args.shift();
		var buffer = (cmd == 'shift') ? 'shiftBuffer' : 'pushBuffer';

		this.isActive = true;
		this[buffer] = this[buffer].concat(args);
		this.update();

		return this.element;
	},

	$merge: function(cmd, items)
	{
		if(typeof cmd != 'string')
			items = cmd;
		var buffer = (cmd == 'shift') ? 'shiftBuffer' : 'pushBuffer';

		this.isActive = true;
		if($.Chain.jobject(items))
			this[buffer] = this[buffer].concat(items.map(function(){return $(this)}).get());
		else if(items instanceof Array)
			this[buffer] = this[buffer].concat(items);
		this.update();

		return this.element;
	},

	$replace: function(cmd, items)
	{
		if(typeof cmd != 'string')
			items = cmd;
		var buffer = (cmd == 'shift') ? 'shiftBuffer' : 'pushBuffer';

		this.isActive = true;
		this.empty();

		if($.Chain.jobject(items))
			this[buffer] = items.map(function(){return $(this)}).get();
		else if(items instanceof Array)
			this[buffer] = items;

		this.update();

		return this.element;
	},

	$remove: function()
	{
		for(var i=0; i<arguments.length; i++)
			this.handler(arguments[i]).item('remove', true);
		this.update();

		return this.element;
	},

	$reorder: function(item1, item2)
	{
		if(item2)
			this.handler(item1).before(this.handler(item2));
		else
			this.handler(item1).appendTo(this.element.chain('anchor'));
		this.update();

		return this.element;
	},

	$empty: function()
	{
		this.empty();
		this.shiftBuffer = [];
		this.pushBuffer = [];
		this.update();

		return this.element;
	},

	$data: function(x)
	{
		return this.handler(x).map(function(){return $(this).item();}).get();
	},

	$link: function(element, collection)
	{
		if(this.linkElement)
		{
			this.linkElement.unbind('update', this.linkUpdater);
			this.linkElement = null;
		}

		element = $(element);
		if(element.length)
		{
			var self = this;
			this.linkElement = element;
			this.linkFunction = function()
			{
				if(typeof collection == 'function')
					try{return collection.call(self.element, self.linkElement)}catch(e){return $().eq(-1)}
				else if(typeof collection == 'string')
					return self.linkElement.items('collection', collection);
				else
					return $().eq(-1);
			};

			this.linkUpdater = function()
			{
				self.element.items('replace', self.linkFunction());
			};

			this.linkElement.bind('update', this.linkUpdater);
			this.linkUpdater();
		}

		return this.element;
	},

	$index: function(item)
	{
		return this.collection('all').index(this.handler(item));
	},

	$collection: function()
	{
		return this.collection.apply(this, Array.prototype.slice.call(arguments));
	},

	$active: function()
	{
		return this.isActive;
	},

	$backup: function()
	{
		if(!this.element.chain('active') || !this.isActive)
			return;

		var buffer = [];
		this.collection('all').each(function(){
			var item = $(this).item();
			if(item)
				buffer.push(item);
		});

		this.pushBuffer = buffer.concat(this.pushBuffer);

		this.empty();

		return this.element;
	},

	$destroy: function()
	{
		this.empty();
		return this.element;
	}
});

// Filtering extension
$.Chain.extend('items', {
	doFilter: function()
	{
		var props = this.searchProperties;
		var text = this.searchText;

		if(text)
		{
			if(typeof text == 'string')
				text = text.toLowerCase();

			var items = this.element.items(true).filter(function(){
				var data = $(this).item();
				if(props)
				{
					for(var i=0; i<props.length; i++)
						if(typeof data[i] == 'string'
							&& !!(typeof text == 'string' ? data[i].toLowerCase() : data[i]).match(text))
							return true;
				}
				else
				{
					for(var i in data)
						if(typeof data[i] == 'string'
							&& !!(typeof text == 'string' ? data[i].toLowerCase() : data[i]).match(text))
							return true;
				}
			});
			this.element.items(true).not(items).hide();
			items.show();
		}
		else
		{
			this.element.items(true).show();
			this.element.unbind('preupdate', this.searchBinding);
			this.searchBinding = null;
		}
	},

	$filter: function(text, properties)
	{
		if(arguments.length == 0)
			return this.update();

		this.searchText = text;

		if(typeof properties == 'text')
			this.searchProperties = [properties];
		else if(properties instanceof Array)
			this.searchProperties = properties;
		else
			this.searchProperties = null;

		if(!this.searchBinding)
		{
			var self = this;
			this.searchBinding = function(event, item){self.doFilter();};
			this.element.bind('preupdate', this.searchBinding);
		}

		return this.update();
	}
});

// Sorting extension
$.Chain.extend('items', {
	doSort: function()
	{
		var name = this.sortName;
		var opt = this.sortOpt;

		var sorter =
		{
			'number': function(a, b){
				return parseFloat(($(a).item()[name]+'').match(/\d+/gi)[0])
					> parseFloat(($(b).item()[name]+'').match(/\d+/gi)[0]);
			},

			'default': function(a, b){
				return $(a).item()[name] > $(b).item()[name];
			}
		};

		if(name)
		{
			var sortfn = opt.fn || sorter[opt.type] || sorter['default'];

			var array = this.element.items(true).get().sort(sortfn);

			array = opt.desc ? array.reverse() : array;

			for(var i=0; i<array.length; i++)
				this.element.chain('anchor').append(array[i]);

			opt.desc = opt.toggle ? !opt.desc : opt.desc;
		}
		else
		{
			this.element.unbind('preupdate', this.sortBinding);
			this.sortBinding = null;
		}
	},

	$sort: function(name, opt)
	{
		if(!name && name !== null && name !== false)
			return this.update();

		if(this.sortName != name)
			this.sortOpt = $.extend({desc:false, type:'default', toggle:false}, opt);
		else
			$.extend(this.sortOpt, opt);

		this.sortName = name;

		if(!this.sortBinding)
		{
			var self = this;
			this.sortBinding = function(event, item){self.doSort();};
			this.element.bind('preupdate', this.sortBinding);
		}

		return this.update();
	}
});

})(jQuery);


/* item.js */
(function($){

$.Chain.service('item', {
	init: function()
	{
		this.isActive = false;
		this.isBuilt = false;
		this.root = this.element;
		this.data = false;
		this.datafn = this.dataHandler;
	},

	handler: function(obj)
	{
		if(typeof obj == 'object')
		{
			this.setData(obj);
			this.isActive = true;

			this.update();
			return this.element;
		}
		else if(typeof obj == 'function')
		{
			this.datafn = obj;

			return this.element;
		}

		if(this.isActive)
			return this.getData();
		else
			return false;
	},

	getData: function()
	{
		this.data = this.datafn.call(this.element, this.data);

		return this.data;
	},

	setData: function(obj)
	{
		var data;
		if($.Chain.jobject(obj) && obj.item())
			data = $.extend({}, obj.item());
		else if($.Chain.jobject(obj))
			data = {}
		else
			data = obj;

		this.data = this.datafn.call(this.element, this.data || data, data);
		if(this.linkElement && this.linkElement[0] != obj[0])
		{
			var el = this.linkFunction();
			if($.Chain.jobject(el) && el.length && el.item())
				el.item(this.data);
		}
	},

	dataHandler: function(a, b)
	{
		if(arguments.length == 2)
			return $.extend(a, b);
		else
			return a;
	},

	update: function()
	{
		return this.element.update();
	},

	build: function()
	{
		// IE Fix
		var fix = this.element.chain('template', 'raw').replace(/jQuery\d+\=\"null\"/gi, "");
		this.element.chain('anchor').html(fix);

		if(!$.Chain.jidentic(this.root, this.element))
		{
			var plugins = this.root.chain('plugin');
			for(var i in plugins)
				plugins[i].apply(this.element, [this.root]);

		}

		this.element.chain('builder').apply(this.element, [this.root]);
		this.isBuilt = true;

		var self = this;
	},

	$update: function()
	{
		if(this.element.chain('active') && this.isActive && !this.isBuilt && this.getData())
			this.build();

		return this.element;
	},

	$replace: function(obj)
	{
		this.data = {};
		this.setData(obj);
		this.isActive = true;
		this.update();
		return this.element;
	},

	$remove: function(noupdate)
	{
		this.element.chain('destroy');
		this.element.remove();

		if(!$.Chain.jidentic(this.root, this.element) && !noupdate)
			this.root.update();

		if(this.$link)
			this.$link(null);
	},

	$active: function()
	{
		return this.isActive;
	},

	$root: function(val)
	{
		if(arguments.length)
		{
			this.root = val;
			this.update();
			return this.element;
		}
		else
		{
			return this.root;
		}
	},

	$backup: function()
	{
		this.isBuilt = false;
	},

	$link: function(element, collection)
	{
		if(this.linkElement)
		{
			this.linkElement.unbind('update', this.linkUpdater);
			this.linkElement = null;
		}

		element = $(element);
		if(element.length)
		{
			var self = this;
			this.isActive = true;
			this.linkElement = element;
			this.linkFunction = function()
			{
				if(typeof collection == 'function')
					try{return collection.call(self.element, self.linkElement)}catch(e){return $().eq(-1)}
				else if(typeof collection == 'string')
					return self.linkElement.items('collection', collection);
				else
					return $().eq(-1);
			};

			this.linkUpdater = function()
			{
				var res = self.linkFunction();
				if(res && res.length)
					self.element.item(res);
			};

			this.linkElement.bind('update', this.linkUpdater);
			this.linkUpdater();
		}

		return this.element;
	}
});

})(jQuery);