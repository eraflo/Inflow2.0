import React, { useState, useEffect } from 'react';
import ColorThief from 'color-thief-browser'; // Import color-thief-browser

export default function PlaylistCard({ playlist }) {
    const [dominantColor, setDominantColor] = useState('#ffffff'); // Default to white

    useEffect(() => {
        const img = new Image();
        img.crossOrigin = 'Anonymous'; // Ensure cross-origin issues are handled
        img.src = playlist.images[0].url;

        img.onload = () => {
            const colorThief = new ColorThief();
            const color = colorThief.getColor(img); // Get the dominant color
            setDominantColor(`rgb(${color[0]}, ${color[1]}, ${color[2]}, 0.7)`);
        };

        img.onerror = () => {
            console.error('Failed to load image.');
        };
    }, [playlist.images]);

    return (
        <a href={playlist.path} className='cardLink' data-turbo-frame="tracks">
            <div className='card'>
                <div className='cover' style={{ backgroundImage: `url(${playlist.images[0].url})` }}>
                    <img src={playlist.images[0].url} style={{ opacity: 0 }} />
                </div>
                <div className='info' style={{ backgroundColor: dominantColor }}>
                    <h3 className='title'>{playlist.name}</h3>
                    <p>
                        ♫ {playlist.tracks.total} sons
                    </p>
                </div>
            </div>
        </a>
    );
}
