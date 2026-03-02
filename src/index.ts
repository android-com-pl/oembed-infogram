import { registerBlockVariation } from '@wordpress/blocks';
import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import { chartBar } from '@wordpress/icons';

domReady(() => {
  registerBlockVariation('core/embed', {
    name: 'infogram',
    title: __('Infogram Embed', 'oembed-infogram'),
    icon: chartBar,
    description: __(
      'Embed an interactive chart or infographic from Infogram.',
      'oembed-infogram',
    ),
    attributes: {
      providerNameSlug: 'infogram',
      responsive: true,
    },
    isActive: ['providerNameSlug'],
    // @ts-ignore
    patterns: [/^https?:\/\/(www\.)?infogram\.com\/.+/i],
  });
});
