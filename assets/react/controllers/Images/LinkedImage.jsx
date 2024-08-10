import React from "react";
import Image from './Image.jsx';

export default class LinkedImage extends Image {
    constructor(props) {
        super(props);
        this.state = {
            src : props.src, 
            alt : props.alt,
            width : props.width,
            height : props.height,
            margin : props.margin,
            link : props.link
        };
    }

    render() {

        const src = this.state.src;
        const alt = this.state.alt;
        const width = this.state.width;
        const height = this.state.height;
        const margin = this.state.margin;
        const link = this.state.link;

        return (
            <a href={link}>
                <Image 
                    src={src} 
                    alt={alt} 
                    width={width} 
                    height={height} 
                    margin={margin} 
                />
            </a>
        );
    }
}