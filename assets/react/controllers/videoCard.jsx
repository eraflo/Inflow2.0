import React from 'react';

export default function (props) {
    return (
        <a href={props.videoLink} target='_blank'>
            <div className='card'>
                <img src={props.videoThumbnail} />
                <h3 className='title'>{props.videoTitle}</h3>
            </div>
        </a>
    );
}