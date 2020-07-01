(function ($) {

	$.fn.serializeArrayAll = function () {
		var rCRLF = /\r?\n/g;
		return this.map(function () {
			return this.elements ? jQuery.makeArray(this.elements) : this;
		}).map(function (i, elem) {
			var val = jQuery(this).val();
			if (val == null) {
				return val == null
			} else if (this.type == "checkbox" && this.checked == false) {
				return {
					name: this.name,
					value: this.checked ? this.value : ''
				}
			} else {
				return jQuery.isArray(val) ?
					jQuery.map(val, function (val, i) {
						return {
							name: elem.name,
							value: val.replace(rCRLF, "\r\n")
						};
					}) : {
						name: elem.name,
						value: val.replace(rCRLF, "\r\n")
					};
			}
		}).get();
	};
	var wpsmi = {
		__instance: undefined
	};

	wpsmi.Application = Backbone.View.extend({
		id: 'wpsmi_modal',
		events: {
			'click .close': 'Close',
			'click .remove': 'Remove',
			'click .save': 'Save',
			'click .attachments .attachment': 'Select',
			'keyup #media-search-input': 'Search',
		},
		ui: {
			nav: undefined,
			content: undefined,
			media: undefined
		},
		templates: {},
		initialize: function (e) {
			'use strict';
			_.bindAll(this, 'render', 'preserveFocus', 'Search', 'Select', 'Close', 'Save', 'Remove');
			this.initialize_templates();
			this.render(e);
			this.backdrop(e);
			this.tabs(e);
		},
		backdrop: function (e) {
			'use strict';

			var plugin = this;

			$(document).on('click', '.media-modal-backdrop', function (e) {
				plugin.Close(e);
			});
		},
		tabs: function (e) {
			'use strict';

			var plugin = this;

			$(document).on('click', '.media-modal-backdrop', function (e) {
				plugin.Close(e);
			});
		},
		initialize_templates: function () {
			this.templates.window = wp.template('wpsmi-modal-window');
			this.templates.backdrop = wp.template('wpsmi-modal-backdrop');
			this.templates.preview = wp.template('wpsmi-modal-preview');
			this.templates.settings = wp.template('wpsmi-modal-settings');
		},
		render: function (e) {
			'use strict';

			var $li = $(e.target).closest('li'),
				menu_item_id = parseInt($li.prop('id').match(/menu-item-([0-9]+)/)[1]),
				wpsmi = {};

			$(e.target).closest('li').find('input.wpsmi-input').each(function (i) {

				var key = $(this).prop('id').match(/wpsmi-input-([a-z]+)/)[1],
					value = $(this).val();

				wpsmi[key] = value;
			});

			this.$el.attr('tabindex', '0')
				.data('menu_item_id', menu_item_id)
				.append(this.templates.window())
				.append(this.templates.backdrop());

			this.ui.preview = this.$('.media-sidebar')
				.append(this.templates.preview(wpsmi))

			this.ui.settings = this.$('.media-sidebar')
				.append(this.templates.settings(wpsmi))

			//this.ui.settings.find('#wpsmi-input-color').wpColorPicker();

			$(document).on('focusin', this.preserveFocus);
			$('body').addClass('modal-open').append(this.$el);
			this.$el.focus();
		},
		preserveFocus: function (e) {
			'use strict';
			if (this.$el[0] !== e.target && !this.$el.has(e.target).length) {
				this.$el.focus();
			}
		},
		Search: function (e) {
			'use strict';
			var $this = $(e.target),
				$icons = this.$el.find('.attachments .attachment');
			$this.on('keyup', function (e) {
				e.preventDefault();
				setTimeout(function () {
					var query = $this.val();
					if (query !== '') {
						$icons.css({
							'display': 'none'
						});
						$icons.filter('[class*="' + query + '"]').css({
							'display': 'block'
						});
					} else {
						$icons.removeAttr('style');
					}
				}, 600);
			});
		},
		Select: function (e) {
			'use strict';
			var $this = $(e.target),
				$filename = this.$el.find('.media-sidebar .filename'),
				$thumbnail = this.$el.find('.media-sidebar .thumbnail > i'),
				$input = this.$el.find('input[name="wpsmi[icon]"]'),
				icon = $this.find('i').attr('class');
			$filename.text(icon);
			$input.val(icon);
			$thumbnail.removeAttr('class').addClass(icon);
		},
		Close: function (e) {
			'use strict';
			e.preventDefault();
			this.undelegateEvents();
			$(document).off('focusin');
			$('body').removeClass('modal-open');
			this.remove();
			wpsmi.__instance = undefined;
		},
		Save: function (e) {
			'use strict';
			e.preventDefault();

			var plugin = this,
				$form = $('form', this.$el),
				menu_item_id = this.$el.data('menu_item_id');

			if (!menu_item_id)
				return;

			if (!$form.length)
				return;

			var $li = $('#menu-to-edit').find('#menu-item-' + menu_item_id),
				$plus = $li.find('.menu-item-wpsmi_plus'),
				$icon = $li.find('.menu-item-wpsmi_icon');

			if (!$li.length)
				return;

			$form.find('.wpsmi-input').each(function (i) {

				var key = $(this).prop('id').match(/wpsmi-input-([a-z]+)/)[1],
					value = $(this).val();

				$li.find('input#wpsmi-input-' + key).val(value);

				if (key === 'icon') {
					if ($icon.length) {
						$icon.remove();
					}
					$plus.before('<i class="menu-item-wpsmi_icon ' + value + '"></i>');
				}
			});

			plugin.Close(e);
		},
		Remove: function (e) {
			'use strict';
			e.preventDefault();

			var plugin = this,
				$form = $('form', this.$el),
				menu_item_id = this.$el.data('menu_item_id');

			if (!menu_item_id)
				return;

			if (!$form.length)
				return;

			var $li = $('#menu-to-edit').find('#menu-item-' + menu_item_id),
				$icon = $li.find('.menu-item-wpsmi_icon');

			if (!$li.length)
				return;

			$form.find('.wpsmi-input').each(function (i) {

				var key = $(this).prop('id').match(/wpsmi-input-([a-z]+)/)[1];

				$li.find('input#wpsmi-input-' + key).val('');

			});

			$icon.remove();

			plugin.Close(e);
		}
	});

	$(document).on('click', '.menu-item-wpsmi_open', function (e) {
		e.preventDefault();
		if (wpsmi.__instance === undefined) {
			wpsmi.__instance = new wpsmi.Application(e);
		}
	});

	$(document).on('click', '#wpsmi_metabox', function (e) {

		var menu_font = $('input:checked', $(this)).val(),
			menu_id = $('#menu').val();

		if ($(e.target).hasClass('save') && menu_font && menu_id) {

			e.preventDefault();

			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'wpsmi_save_nav_menu',
					menu_id: menu_id,
					menu_font: menu_font,
					nonce: wpsmi_l10n.nonce
				},
				beforeSend: function () {},
				complete: function () {},
				error: function () {
					alert('Error!');
				},
				success: function (response) {
					location.reload();
				}
			});

		}
	});

})(jQuery);
