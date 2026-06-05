import { __, sprintf } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	ToggleControl,
	Spinner,
	Placeholder,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
import { useState } from '@wordpress/element';

const TOGGLE_FIELDS = [
	[ 'showTemperature', __( 'Temperature',   'weather-posts-block' ) ],
	[ 'showFeelsLike',   __( 'Feels like',    'weather-posts-block' ) ],
	[ 'showCondition',   __( 'Condition',     'weather-posts-block' ) ],
	[ 'showHumidity',    __( 'Humidity',      'weather-posts-block' ) ],
	[ 'showPressure',    __( 'Pressure',      'weather-posts-block' ) ],
	[ 'showWindSpeed',   __( 'Wind speed',    'weather-posts-block' ) ],
	[ 'showSunrise',     __( 'Sunrise',       'weather-posts-block' ) ],
	[ 'showSunset',      __( 'Sunset',        'weather-posts-block' ) ],
	[ 'showLocation',    __( 'Location name', 'weather-posts-block' ) ],
];

function PostCard( { postId } ) {
	const { post, image } = useSelect(
		( select ) => {
			const p = select( coreStore ).getEntityRecord( 'postType', 'post', postId );
			const img = p?.featured_media
				? select( coreStore ).getMedia( p.featured_media )
				: null;
			return { post: p, image: img };
		},
		[ postId ]
	);

	if ( ! post ) {
		return (
			<div className="wpb-post-card">
				<Spinner />
			</div>
		);
	}

	const imgSrc =
		image?.media_details?.sizes?.medium_large?.source_url ||
		image?.source_url;

	return (
		<article className="wpb-post-card">
			{ imgSrc && (
				<div className="wpb-post-card__image-wrap">
					<img
						src={ imgSrc }
						alt={ image?.alt_text || '' }
						className="wpb-post-card__image"
					/>
				</div>
			) }
			<div className="wpb-post-card__body">
				<h3 className="wpb-post-card__title">
					{ post.title?.rendered }
				</h3>
			</div>
		</article>
	);
}

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( { className: 'wpb-block' } );
	const { postIds, latitude, longitude } = attributes;

	const [ search, setSearch ] = useState( '' );

	const searchResults = useSelect(
		( select ) =>
			select( coreStore ).getEntityRecords( 'postType', 'post', {
				per_page: 20,
				search,
				status: 'publish',
			} ),
		[ search ]
	);

	function togglePost( id ) {
		if ( postIds.includes( id ) ) {
			setAttributes( { postIds: postIds.filter( ( p ) => p !== id ) } );
		} else if ( postIds.length < 2 ) {
			setAttributes( { postIds: [ ...postIds, id ] } );
		}
	}

	const hasLocation = latitude !== 0 || longitude !== 0;

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Posts', 'weather-posts-block' ) }>
					<p className="components-base-control__help">
						{ __( 'Select up to 2 posts.', 'weather-posts-block' ) }
					</p>
					<TextControl
						label={ __( 'Search posts', 'weather-posts-block' ) }
						value={ search }
						onChange={ setSearch }
					/>
					{ searchResults === null && <Spinner /> }
					{ ( searchResults || [] ).map( ( post ) => (
						<div key={ post.id } style={ { marginBottom: '6px' } }>
							<label>
								<input
									type="checkbox"
									checked={ postIds.includes( post.id ) }
									disabled={
										! postIds.includes( post.id ) &&
										postIds.length >= 2
									}
									onChange={ () => togglePost( post.id ) }
									style={ { marginRight: '6px' } }
								/>
								{ post.title.rendered }
							</label>
						</div>
					) ) }
				</PanelBody>

				<PanelBody title={ __( 'Location', 'weather-posts-block' ) }>
					<TextControl
						label={ __( 'Latitude', 'weather-posts-block' ) }
						type="number"
						value={ String( latitude ) }
						onChange={ ( v ) =>
							setAttributes( { latitude: parseFloat( v ) || 0 } )
						}
					/>
					<TextControl
						label={ __( 'Longitude', 'weather-posts-block' ) }
						type="number"
						value={ String( longitude ) }
						onChange={ ( v ) =>
							setAttributes( { longitude: parseFloat( v ) || 0 } )
						}
					/>
				</PanelBody>

				<PanelBody
					title={ __( 'Weather Fields', 'weather-posts-block' ) }
					initialOpen={ false }
				>
					{ TOGGLE_FIELDS.map( ( [ key, label ] ) => (
						<ToggleControl
							key={ key }
							label={ label }
							checked={ !! attributes[ key ] }
							onChange={ ( v ) => setAttributes( { [ key ]: v } ) }
						/>
					) ) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ postIds.length === 0 && ! hasLocation ? (
					<Placeholder
						label={ __( 'Weather Posts Block', 'weather-posts-block' ) }
						instructions={ __(
							'Select posts and set coordinates in the sidebar.',
							'weather-posts-block'
						) }
					/>
				) : (
					<>
						<div className="wpb-block__weather-panel">
							<p className="wpb-editor-notice">
								{ hasLocation
									? sprintf(
										/* translators: 1: latitude, 2: longitude */
										__( 'Weather for %1$s°, %2$s° — loads on frontend.', 'weather-posts-block' ),
										latitude,
										longitude
									)
									: __( 'Set coordinates in the sidebar to show weather.', 'weather-posts-block' )
								}
							</p>
						</div>
						<div className="wpb-block__posts">
							{ postIds.map( ( id ) => (
								<PostCard key={ id } postId={ id } />
							) ) }
						</div>
					</>
				) }
			</div>
		</>
	);
}
