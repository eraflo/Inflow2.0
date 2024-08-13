import React, { useState } from "react";
import UILabelSemiBold from "../Texts/UILabelSemiBold";

export default class ClassicButton extends React.Component {
        
    constructor(props) {
        super(props);
        this.state = {
            width : props.width,
            height : props.height,
            padding : props.padding,
            margin : props.margin,
            text : props.text, 
            link : props.link,
            color : props.color,
            text_color : props.text_color
        };
    }


    render() {

        const text = this.state.text;
        const link = this.state.link;
        const color = this.state.color;
        const text_color = this.state.text_color;
        const width = this.state.width;
        const height = this.state.height;
        const padding = this.state.padding;
        const margin = this.state.margin;

        const style = {
            width: width,
            height: height,
            background: color,
            padding: padding,
            margin: margin
        };
        
        return (
            <div className="classic-button" style={style}>
                <a href={link}>
                    <UILabelSemiBold 
                        text={text} 
                        color={text_color} 
                    />
                </a>
            </div>
        );
    }
}