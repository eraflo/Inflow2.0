import React from 'react';
import Slider from 'react-slick';
import "slick-carousel/slick/slick.css"; 
import "slick-carousel/slick/slick-theme.css";
import PlaylistCard from './PlaylistCard';

function SampleNextArrow(props) {
  const { className, style, onClick } = props;
  return (
    <div
      className={className}
      style={{ ...style, display: "flex"}}
      onClick={onClick}
    ></div>
  );
}

function SamplePrevArrow(props) {
  const { className, style, onClick } = props;
  return (
    <div
      className={className}
      style={{ ...style, display: "flex"}}
      onClick={onClick}
    ></div>
  );
}

const Carousel = ({ playlists }) => {
    const settings = {
      dots: true,  // Show navigation dots
      infinite: true,  // Infinite loop scrolling
      speed: 500,  // Transition speed in milliseconds
      slidesToShow: 3,  // Number of slides to show at a time
      slidesToScroll: 1,  // Number of slides to scroll at a time
      autoplay: true,  // Enable autoplay
      autoplaySpeed: 3000,  // Autoplay interval in milliseconds
      nextArrow: <SampleNextArrow />,
      prevArrow: <SamplePrevArrow />
    };
  
    return (
        <Slider {...settings}>
        {playlists.map((playlist) => (
            <PlaylistCard key={playlist.id} playlist={playlist}></PlaylistCard>
        ))}
        </Slider>
    );
  };
  
  export default Carousel;