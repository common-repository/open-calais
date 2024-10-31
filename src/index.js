/**
 * Internal dependencies
 */
//import './block-editor-styles.scss';
import OpenCalaisSelector from './block-editor-selector';

/**
 * External dependecies
 */
import { addFilter } from '@wordpress/hooks';

/**
 * Override the term selector component
 */
function filterTagSelector( OriginalComponent ) {

	return function( props ) {

		// Limit updates to post_tag taxonomy
		if ( props.slug === 'post_tag' ) {
			return <OpenCalaisSelector {...props} />;
		} else {
			return <OriginalComponent {...props} />;
		}
	}
};

addFilter(
	'editor.PostTaxonomyType',
	'open-calais',
	filterTagSelector
);
