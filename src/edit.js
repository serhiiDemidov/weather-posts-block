import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
    PanelBody,
    TextControl,
    ToggleControl,
    Spinner,
    Placeholder,
} from '@wordpress/components';
import { ServerSideRender } from '@wordpress/server-side-render';
import { useSelect } from '@wordpress/data';
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

export default function Edit( { attributes, setAttributes } ) {
    const blockProps = useBlockProps();
    const { postIds, latitude, longitude } = attributes;

    const [ search, setSearch ] = useState( '' );

    const posts = useSelect(
        ( select ) =>
            select( 'core' ).getEntityRecords( 'postType', 'post', {
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
                    { posts === null && <Spinner /> }
                    { ( posts || [] ).map( ( post ) => (
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
                <ServerSideRender
                    block="weather-posts-block/main"
                    attributes={ attributes }
                    EmptyResponsePlaceholder={ () => (
                        <Placeholder
                            label={ __( 'Weather Posts Block', 'weather-posts-block' ) }
                            instructions={ __(
                                'Select posts and set coordinates in the sidebar.',
                                'weather-posts-block'
                            ) }
                        />
                    ) }
                />
            </div>
        </>
    );
}
