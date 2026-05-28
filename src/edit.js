import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
    const blockProps = useBlockProps();

    return (
        <div { ...blockProps }>
            <p>{ __( 'Weather Posts Block — editor view coming soon.', 'weather-posts-block' ) }</p>
        </div>
    );
}
