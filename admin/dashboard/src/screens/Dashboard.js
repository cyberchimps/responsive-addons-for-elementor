import { __ } from "@wordpress/i18n";
import { useNavigate } from 'react-router-dom';
import { useContext, useState } from "react";
import { WidgetContext } from "../WidgetContext";
import WidgetCard from "../components/WidgetCard";
import InstallButton from "../components/InstallButton";
import Icons from "../icons";

const Dashboard = () => {
    return (
        <>
            <HeroSection />
            <WidgetSection />
            <ExtendAndQuickAccess />
            <StarterTemplates />
        </>
    )
}

const HeroSection = () => {
    return (
        <div className="xl:mx-14 md:mx-15 mt-8 mb-16 rounded-lg bg-[linear-gradient(256.02deg,#080084_19.96%,#2563EB_90.75%)]">
            <div className="flex flex-col gap-6 py-15 px-6 sm:py-14 sm:px-14 pl-15">
                <p className="text-white font-bold text-5xl sm:text-4xl md:text-5xl leading-tight m-0">{__('Welcome to Responsive Addons for Elementor', 'responsive-addons-for-elementor')}</p>
                <p className="max-w-175 text-blue-50 font-medium text-sm leading-relaxed m-0">{__('Responsive Addons for Elementor plugin enhances the Elementor page builder with 80+ advanced widgets, 5+ extensions, and ready-to-use sections helping you build powerful websites faster.', 'responsive-addons-for-elementor')}</p>
                <p className="m-0">
                    <button onClick={() => window.location.href = localize?.pageurl} className="flex items-center gap-1 py-2.5 px-3.5 text-blue-500 leading-5 cursor-pointer bg-white rounded-md font-medium border-0">{Icons.createPage} {__('Create a Page', 'responsive-addons-for-elementor')}
                    </button>
                </p>
            </div>
        </div>
    )
};

const WidgetSection = () => {

    const navigate = useNavigate();

    const { widgetsList, activeWidgetsCount, inactiveWidgetsCount } = useContext(WidgetContext);

    return (
        <div className="xl:mx-14 md:mx-15 mb-16">
            <div className="flex justify-between">
                <div className="flex items-center gap-5">
                    <p className="text-2xl font-medium m-0">{__('Widgets', 'responsive-addons-for-elementor')}</p>
                    <div className="flex items-center gap-3">
                        <span className="flex items-center rounded-3xl border border-blue-300 text-xs font-normal text-blue-500 bg-blue-100 px-2.5 py-1">{__('Total Widgets', 'responsive-addons-for-elementor')} {widgetsList.length}</span>
                        <span className="flex items-center rounded-3xl border border-green-300 text-xs font-normal text-green-800 bg-green-100 px-2.5 py-1">{__('Active', 'responsive-addons-for-elementor')} {activeWidgetsCount}</span>
                        <span className="flex items-center rounded-3xl border border-red-300 text-xs font-normal text-red-500 bg-red-100 px-2.5 py-1">{__('Inactive', 'responsive-addons-for-elementor')} {inactiveWidgetsCount}</span>
                    </div>
                </div>
                <div>
                    <button onClick={() => navigate('/widgets')} className="cursor-pointer rounded-md border border-blue-600 text-blue-600 hover:bg-blue-100 text-sm font-medium px-5 py-2">{__('View All', 'responsive-addons-for-elementor')}</button>
                </div>
            </div>
            <p className="font-normal text-base text-desc text-[#4B5563] mt-2 mb-6">{__('Manage which widgets are enabled for your website', 'responsive-addons-for-elementor')}</p>
            <CardSection />
        </div>
    )
};

const CardSection = () => {

    const specificWidgets = ['advanced-tabs', 'dual-color-header', 'flip-box', 'google-map', 'icon-box', 'image-gallery', 'media-carousel', 'posts', 'pricing-table', 'stacking-cards', 'testimonial-slider', 'timeline'];

    const { widgetsList } = useContext(WidgetContext)

    const showWidgets = widgetsList.filter(item => specificWidgets.includes(item.title));

    return (
        <div className="mt-8 mb-16">
            <div className="grid sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 p-3 bg-slate-100 border border-slate-200 rounded-lg">
                {showWidgets.map((current) => <WidgetCard data={current} />)}
            </div>
        </div>
    )
}

/* ---------------------------------------------------------- */
/* Icons for Upgrade / Connect / Connected cards               */
/* ---------------------------------------------------------- */

const CheckIcon = () => (
    <svg width="15" height="13" viewBox="0 0 15 13" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0.599609 8.63318L4.59961 11.6332L13.5996 0.633179" stroke="#00862F" strokeWidth="2" />
    </svg>
);

const InfoIcon = () => (
    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="7" cy="7" r="6.25" stroke="#A3A3A3" strokeWidth="1.5" />
        <path d="M7 6.25V10" stroke="#A3A3A3" strokeWidth="1.5" strokeLinecap="round" />
        <circle cx="7" cy="4.25" r="0.9" fill="#A3A3A3" />
    </svg>
);

const CrownIcon = () => (
    <svg width="15" height="14" viewBox="0 0 15 14" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2.7725 13.3333C2.65472 13.3333 2.55583 13.2933 2.47583 13.2133C2.39583 13.1333 2.35583 13.0342 2.35583 12.9158C2.35583 12.7975 2.39583 12.6986 2.47583 12.6192C2.55583 12.5397 2.65472 12.5 2.7725 12.5H12.2275C12.3458 12.5 12.4447 12.54 12.5242 12.62C12.6036 12.7 12.6436 12.7992 12.6442 12.9175C12.6447 13.0358 12.6047 13.1347 12.5242 13.2142C12.4436 13.2936 12.3447 13.3333 12.2275 13.3333H2.7725ZM3.42 10.93C3.09222 10.93 2.80667 10.8272 2.56333 10.6217C2.32 10.4161 2.16389 10.155 2.095 9.83833L1.1 4.5625C1.07222 4.57361 1.04111 4.57972 1.00667 4.58083C0.971667 4.5825 0.940278 4.58333 0.9125 4.58333C0.650833 4.58333 0.433333 4.49444 0.26 4.31667C0.0866667 4.13889 0 3.92333 0 3.67C0 3.40722 0.0869444 3.18389 0.260833 3C0.434167 2.81667 0.651945 2.725 0.914167 2.725C1.17639 2.725 1.39944 2.81667 1.58333 3C1.76667 3.18389 1.85833 3.40722 1.85833 3.67C1.85833 3.72778 1.85611 3.78139 1.85167 3.83083C1.84722 3.88028 1.83083 3.92917 1.8025 3.9775L4.1025 4.90333C4.20917 4.94611 4.31611 4.95167 4.42333 4.92C4.53 4.88778 4.62056 4.82361 4.695 4.7275L6.94667 1.6775C6.82611 1.59861 6.73056 1.49417 6.66 1.36417C6.58944 1.23472 6.55417 1.095 6.55417 0.945C6.55417 0.682778 6.64611 0.459722 6.83 0.275833C7.01333 0.0919445 7.23639 0 7.49917 0C7.76139 0 7.98472 0.0916666 8.16917 0.275C8.35361 0.458333 8.44583 0.680556 8.44583 0.941667C8.44583 1.09944 8.41056 1.24167 8.34 1.36833C8.26944 1.49611 8.17389 1.59917 8.05333 1.6775L10.305 4.7275C10.3794 4.82361 10.47 4.8875 10.5767 4.91917C10.6839 4.95194 10.7908 4.94694 10.8975 4.90417L13.1975 3.9775C13.1825 3.93306 13.1694 3.88444 13.1583 3.83167C13.1472 3.77833 13.1417 3.72444 13.1417 3.67C13.1417 3.40722 13.2283 3.18389 13.4017 3C13.575 2.81667 13.7928 2.725 14.055 2.725C14.3172 2.725 14.5403 2.81667 14.7242 3C14.9081 3.18389 15 3.40722 15 3.67C15 3.92222 14.9078 4.1375 14.7233 4.31583C14.5389 4.49417 14.315 4.58333 14.0517 4.58333C14.0306 4.58333 14.0067 4.58 13.98 4.57333C13.9533 4.56667 13.9239 4.56306 13.8917 4.5625L12.905 9.8375C12.8356 10.1553 12.6794 10.4167 12.4367 10.6217C12.1939 10.8267 11.9083 10.9294 11.58 10.93H3.42Z" fill="#FACC15" />
    </svg>
);

const ConnectedCheckIcon = () => (
    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><g clipPath="url(#clip0_985_4313_rael)"><path fillRule="evenodd" clipRule="evenodd" d="M0 7.5C0 5.51088 0.790176 3.60322 2.1967 2.1967C3.60322 0.790176 5.51088 0 7.5 0C9.48912 0 11.3968 0.790176 12.8033 2.1967C14.2098 3.60322 15 5.51088 15 7.5C15 9.48912 14.2098 11.3968 12.8033 12.8033C11.3968 14.2098 9.48912 15 7.5 15C5.51088 15 3.60322 14.2098 2.1967 12.8033C0.790176 11.3968 0 9.48912 0 7.5ZM7.072 10.71L11.39 5.312L10.61 4.688L6.928 9.289L4.32 7.116L3.68 7.884L7.072 10.71Z" fill="#15803D"></path></g><defs><clipPath id="clip0_985_4313_rael"><rect width="15" height="15" fill="white"></rect></clipPath></defs></svg>
);

const ConnectionIcon = () => (
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="9" stroke="#4338CA" strokeWidth="1.5" />
        <path d="M12 3C14.5 5.5 15.75 8.5 15.75 12C15.75 15.5 14.5 18.5 12 21C9.5 18.5 8.25 15.5 8.25 12C8.25 8.5 9.5 5.5 12 3Z" stroke="#4338CA" strokeWidth="1.5" />
        <path d="M3.5 9H20.5" stroke="#4338CA" strokeWidth="1.5" />
        <path d="M3.5 15H20.5" stroke="#4338CA" strokeWidth="1.5" />
    </svg>
);

const FeatureListItem = ({ text, info = false, tooltip = null }) => (
    <div className="flex items-center gap-3">
        <span className="flex-shrink-0"><CheckIcon /></span>
        <span className="text-[#525252] text-[16px] leading-6 font-normal">{text}</span>
        {info && (
            <span className="rael-info-tooltip">
                <InfoIcon />
                {tooltip && (
                    <span className="rael-tooltip-content">{tooltip}</span>
                )}
            </span>
        )}
    </div>
);

/* ---------------------------------------------------------- */
/* Upgrade / Connect / Connected cards                         */
/* ---------------------------------------------------------- */

const UpgradeToProCard = () => {

    const features = [
        { text: 'Premium Starter Templates' },
        { text: 'Advanced Customizer Settings' },
        { text: 'WooCommerce Customizer Settings' },
        { text: 'Mega Menu' },
        { text: 'AI Content Creation' },
        { text: 'White Label', info: true, tooltip: 'Available in Business & Agency Plans' },
        { text: 'Site Builder', info: true, tooltip: 'Available in Business & Agency Plans' },
        { text: 'Import/Export settings', info: true, tooltip: 'Available in Business & Agency Plans' },
        { text: 'VIP Support' },
    ];

    return (
        <div className="p-6 bg-white rounded-md">
            <div className="flex flex-col gap-7">
                <span className="text-[#1F2937] font-medium text-lg leading-6">{__('Upgrade To Responsive Pro', 'responsive-addons-for-elementor')}</span>
                <div className="flex flex-col gap-3">
                    {features.map((feature, index) => (
                        <FeatureListItem
                            key={index}
                            text={__(feature.text, 'responsive-addons-for-elementor')}
                            info={feature.info}
                            tooltip={feature.tooltip ? __(feature.tooltip, 'responsive-addons-for-elementor') : null}
                        />
                    ))}
                </div>
                <button
                    onClick={() => window.open('https://cyberchimps.com/pricing/?utm_source=wpdash&utm_medium=rael&utm_campaign=rael-home-tab&utm_content=upgrade', '_blank')}
                    className="self-start flex items-center gap-2 py-2.5 px-3.5 text-white text-sm leading-5 font-semibold cursor-pointer upgrade-button-bg rounded-md border-0 transition-colors"
                >
                    <CrownIcon />
                    {__('Upgrade Now', 'responsive-addons-for-elementor')}
                </button>
            </div>
        </div>
    )
};

const WebsiteConnectedCard = () => {
    return (
        <div className="p-6 bg-white rounded-md flex flex-col gap-4">
            <span className="w-fit flex items-center gap-2 py-1 px-3 bg-green-50 border border-green-200 rounded-3xl connected-span">
                <span className="flex items-center justify-center w-4 h-4 bg-green-600 rounded-3xl">
                    <ConnectedCheckIcon />
                </span>
                <span className="text-green-800 text-sm leading-5 font-medium">
                    {__('Connected', 'responsive-addons-for-elementor')}
                </span>
            </span>

            <div className="flex flex-col gap-2">
                <span className="text-[#1F2937] font-medium text-lg leading-7">
                    {__('Your Website Is Connected!', 'responsive-addons-for-elementor')}
                </span>
                <p className="text-desc text-sm leading-5 m-0">
                    {__('You are using', 'responsive-addons-for-elementor')}{' '}
                    <span className="font-semibold text-[#374151]">{__('Responsive Addons for Elementor + Responsive Pro plugin.', 'responsive-addons-for-elementor')}</span>
                </p>
            </div>

            <p className="font-semibold text-[#374151] text-sm leading-5 m-0">
                {__('Email:', 'responsive-addons-for-elementor')}{' '}
                <span className="font-normal text-[#374151]">{localize?.userEmail}</span>
            </p>

            <button
                className="rst-delete-auth w-fit text-red-500 text-sm leading-5 font-medium bg-transparent border-0 p-0 cursor-pointer hover:text-red-600"
            >
                {__('Disconnect', 'responsive-addons-for-elementor')}
            </button>
        </div>
    )
};

const ConnectWebsiteCard = () => {
    return (
        <div className="p-6 bg-white rounded-md">
            <div className="flex flex-col gap-4">
                <div className="w-11 h-11 flex items-center justify-center bg-blue-100 rounded-lg">
                    <ConnectionIcon />
                </div>

                <div className="flex flex-col gap-2">
                    <span className="text-[#1F2937] font-semibold text-lg leading-7">
                        {__('Connect Your Website', 'responsive-addons-for-elementor')}
                    </span>
                    <p className="text-desc text-sm leading-6 m-0">
                        {__('Connect your website to the Responsive Pro plugin to unlock access and seamlessly import premium templates directly to your site.', 'responsive-addons-for-elementor')}
                    </p>
                </div>

                <div className="flex flex-col gap-3">
                    <button className="rst-start-auth rst-start-auth-exist py-2.5 px-5 text-white leading-5 cursor-pointer connect-button rounded-md font-medium border-0 connect-exist connection-border">
                        {__('Connect with existing account', 'responsive-addons-for-elementor')}
                    </button>
                </div>
            </div>
        </div>
    )
};

const ExtendAndQuickAccess = () => {

    const navigate = useNavigate();

    const [rplusText, setRplusText] = useState(localize?.rst_status);
    const [rbeaText, setRbeaText] = useState(localize?.rbea_status);
    const [themeText, setThemeText] = useState(localize?.responsive_status);

    const planDetails = localize?.plan_details;
    const isConnectedPlan = planDetails;

    return (
        <div className="xl:flex lg:block justify-between xl:mx-14 md:mx-15 mt-8 mb-16 gap-12">
            <div className="xl:w-2/3 lg:w-full">
                <p className="font-medium text-2xl m-0">{__('Extend Your Website', 'responsive-addons-for-elementor')}</p>
                <p className="font-normal text-base text-desc mt-2 mb-6">{__("Powerful tools to enhance your site's functionality", 'responsive-addons-for-elementor')}</p>
                <div className="grid md:grid-cols-2 gap-6 w-full p-3 bg-slate-100 border border-slate-200 rounded-md">
                    <PluginCard title={__('Starter Templates', 'responsive-addons-for-elementor')} description={__('150+ Ready to Import Designer-Made Website Starter Templates.', 'responsive-addons-for-elementor')} image="rst_sm_logo">
                        <button onClick={() => navigate('/templates')} className="mt-1.125 py-2.5 px-3.5 border-0 bg-blue-600 hover:bg-blue-900 rounded-md text-white text-sm leading-5 font-medium cursor-pointer">{__('Explore Templates', 'responsive-addons-for-elementor')}</button>
                    </PluginCard>

                    <PluginCard title={__('Responsive Starter Templates', 'responsive-addons-for-elementor')} description={__('Get Advanced modules: Site Builder, Fonts, WooCommerce, and more.', 'responsive-addons-for-elementor')} image="rst_sm_logo">
                        <InstallButton
                            type="plugin"
                            status={localize?.rst_status}
                            nonce={localize.rst_nonce}
                            redirect={localize.rst_redirect}
                            buttonText={rplusText}
                            setButtonText={setRplusText}
                            slug="responsive-add-ons"
                        />
                    </PluginCard>

                    <PluginCard title={__('Responsive Blocks', 'responsive-addons-for-elementor')} description={__('50+ Blocks to Enhance Your WordPress Block Editor Experience.', 'responsive-addons-for-elementor')} image="rbea_logo">
                        <InstallButton
                            type="plugin"
                            status={localize?.rbea_status}
                            nonce={localize.rbea_nonce}
                            redirect={localize.rbea_redirect}
                            buttonText={rbeaText}
                            setButtonText={setRbeaText}
                            slug="responsive-block-editor-addons"
                        />
                    </PluginCard>

                    <PluginCard title={__('Responsive Theme', 'responsive-addons-for-elementor')} description={__('Craft Stunning Websites Effortlessly with the Responsive Theme.', 'responsive-addons-for-elementor')} image="responsive_logo">
                        <InstallButton
                            type="theme"
                            status={localize?.responsive_status}
                            nonce={localize.responsive_nonce}
                            redirect={localize.responsive_redirect}
                            buttonText={themeText}
                            setButtonText={setThemeText}
                            slug="responsive"
                        />
                    </PluginCard>
                </div>
            </div>
            <div className="xl:w-1/3 lg-w-full max-xl:mt-8 flex flex-col gap-6">
                {(() => {
                    if (isConnectedPlan) {
                        return <WebsiteConnectedCard />;
                    }
                    if (localize?.isResponsiveXActivated) {
                        return <ConnectWebsiteCard />;
                    }
                    return <UpgradeToProCard />;
                })()}

                <div>
                    <p className="font-medium text-2xl m-0">{__('Quick Access', 'responsive-addons-for-elementor')}</p>
                    <p className="font-normal text-base text-desc mt-2 mb-6">{__('Helpful resources & links', 'responsive-addons-for-elementor')}</p>
                    <div className="flex flex-col gap-3 p-3 bg-slate-100 rounded-md border border-slate-200">
                        <div className="flex gap-5 i p-3.5 bg-white rounded-md">
                            <span className="flex items-center self-start p-2.5 rounded-md border border-green-500">{Icons.star}</span>
                            <div>
                                <a href={localize.review_link} target="_blank" className="text-lg leading-7 font-medium text-desc text-[#4B5563] no-underline">{__('Rate Us', 'responsive-addons-for-elementor')}</a>
                                <p className="text-sm leading-5 font-normal text-desc m-0">{__('Share your experience', 'responsive-addons-for-elementor')}</p>
                            </div>
                        </div>
                        <div className="flex gap-5 p-3.5 bg-white rounded-md">
                            <span className="flex items-center self-start p-2.5 rounded-md border border-yellow-500">{Icons.community}</span>
                            <div>
                                <a href="https://www.facebook.com/groups/responsive.theme" target="_blank" className="text-lg leading-7 font-medium text-desc text-[#4B5563] no-underline">{__('Join the Community', 'responsive-addons-for-elementor')}</a>
                                <p className="text-sm leading-5 font-normal text-desc m-0">{__('Connect with other users', 'responsive-addons-for-elementor')}</p>
                            </div>
                        </div>
                        <div className="flex gap-5 p-3.5 bg-white rounded-md">
                            <span className="flex items-center self-start p-2.5 rounded-md border border-blue-200">{Icons.help}</span>
                            <div>
                                <a href="https://wordpress.org/support/plugin/responsive-addons-for-elementor/" target="_blank" className="text-lg leading-7 font-medium text-blue-500 no-underline">{__('Support', 'responsive-addons-for-elementor')}</a>
                                <p className="text-sm leading-5 font-normal text-desc m-0">{__('Get help from our support team', 'responsive-addons-for-elementor')}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}

const PluginCard = ({ title, description, image, children }) => {
    return (
        <div className="p-6 bg-white rounded-md transition-shadow hover:[box-shadow:0px_10px_15px_-3px_rgba(0,0,0,0.1)]">
            <div className="flex justify-between items-start">
                <img className="w-12.5 h-12.5" src={localize.raelurl + 'admin/images/' + image + '.svg'} alt="Responsive Logo" />
                <span className="py-1 px-2.5 text-xs leading-4 font-medium text-green-800 bg-green-50 border border-green-300 rounded cap">{__('Free', 'responsive-addons-for-elementor')}</span>
            </div>
            <p className="mt-1.125 mb-2 text-base leading-6 font-medium">{title}</p>
            <p className="text-sm leading-5 font-normal">{description}</p>
            {children}
        </div>
    );
}

const StarterTemplates = () => {

  const navigate = useNavigate();

  const templates = ['Real Estate', 'Business', 'Jewellery Shop', 'Interior Design Firm'];

  return (
    <div className="xl:mx-14 md:mx-15 mt-8 mb-16">
      <div className="flex justify-between mb-6">
        <div>
          <p className="text-2xl leading-8 font-medium m-0">{__('Starter Templates', 'responsive-addons-for-elementor')}</p>
          <p className="mt-2 text-base leading-6 font-normal text-desc">{__('Pre-designed templates to kickstart your website in seconds', 'responsive-addons-for-elementor')}</p>
        </div>
        <button onClick={() => navigate('/templates')} className="rounded-md border border-blue-600 text-blue-600 hover:bg-blue-100 text-sm font-medium px-5 py-2 self-baseline cursor-pointer">{__('View All Templates', 'responsive-addons-for-elementor')}</button>
      </div>
      <div className="flex justify-between gap-6">
        {templates?.map((template, index) => (
          <div className="bg-white border border-slate-200 rounded-md transition-shadow hover:[box-shadow:0px_10px_10px_-5px_rgba(0,0,0,0.04)]">
            <img className="w-full" src={localize.raelurl + `admin/images/template${index + 1}.jpg`} alt={template} />
            <p className="py-6 pl-6 text-base leading-6 font-normal text-black">{template}</p>
          </div>
        ))}
      </div>
    </div>
  );
}

export default Dashboard