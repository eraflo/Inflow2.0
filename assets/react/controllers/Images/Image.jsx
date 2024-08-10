import React from "react";

export default class Image extends React.Component {    
    constructor(props) {
        super(props);
        this.state = {
            src : props.src, 
            alt : props.alt,
            width : props.width,
            height : props.height,
            margin : props.margin
        };
    }

    render() {

        const src = window.location.origin + "/build" + this.state.src;
        const alt = this.state.alt;
        const width = this.state.width;
        const height = this.state.height;
        const margin = this.state.margin;

        return (
            <div style={
                {
                    width: width,
                    height: height,
                    margin: margin,
                    backgroundImage: `url(${src})`,
                    backgroundSize: 'cover',
                    backgroundRepeat: 'no-repeat',
                    backgroundPosition: 'center'
                }
            }>
            </div>
        );
    }
}