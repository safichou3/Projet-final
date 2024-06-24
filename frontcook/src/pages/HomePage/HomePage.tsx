import {Splide, SplideSlide, SplideTrack} from '@splidejs/react-splide';
// import '@splidejs/react-splide/css';
import '@splidejs/react-splide/css/core';
import style from './HomePage.module.scss'
import img1 from '../../assets/images/Group4.png'
import img2 from '../../assets/images/Group5.png'

const HomePage = () => {

    return (
        <div className="wrap">
            <div className="p-50">

                <h1>Delicious food for you</h1>

                <input className={style.searchbar} type="search" placeholder="Chercher"/>

                <a href="" className={style.sliderLink}>Voir plus</a>

                <Splide hasTrack={false}
                        options={{
                            rewind: false,
                            // type: 'loop'
                            arrows: false
                        }}
                >

                    <SplideTrack>
                        <SplideSlide>Plats</SplideSlide>
                        <SplideSlide>Desserts</SplideSlide>
                        <SplideSlide>Boissons</SplideSlide>
                        <SplideSlide>Entrées</SplideSlide>
                        <SplideSlide>Petits plus</SplideSlide>
                        {/*<SplideSlide></SplideSlide>*/}
                    </SplideTrack>

                </Splide>

                <Splide hasTrack={false}
                        options={{
                            rewind: false,
                            // type: 'loop'
                            arrows: false
                        }}
                >

                    <SplideTrack>
                        <SplideSlide><img src={img1} alt="Image 1"/></SplideSlide>
                        <SplideSlide><img src={img2} alt="Image 2"/></SplideSlide>
                        <SplideSlide><img src={img1} alt="Image 1"/></SplideSlide>
                        <SplideSlide><img src={img2} alt="Image 2"/></SplideSlide>
                        <SplideSlide><img src={img1} alt="Image 1"/></SplideSlide>
                        <SplideSlide><img src={img2} alt="Image 2"/></SplideSlide>
                        <SplideSlide><img src={img1} alt="Image 1"/></SplideSlide>
                        <SplideSlide><img src={img2} alt="Image 2"/></SplideSlide>
                    </SplideTrack>

                </Splide>

            </div>
        </div>
    );
};

export default HomePage;