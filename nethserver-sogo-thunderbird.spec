Summary: SOGo Thunderbird plugin for NethServer
Name: nethserver-sogo-thunderbird
Version: 1.1.1
Release: 1
License: GPL
URL: %{url_prefix}/%{name} 
Source0: %{name}-%{version}.tar.gz
BuildArch: noarch

Requires: nethserver-httpd >= 1.0.1-2
Requires: nethserver-php >= 1.0.0
Requires: zip, unzip, patch

BuildRequires: perl
BuildRequires: nethserver-devtools 


%description
SOGo Thunderbird plugin for NethServer

%prep
%setup

%build
perl createlinks

%install
rm -rf $RPM_BUILD_ROOT
(cd root; find . -depth -print | cpio -dump $RPM_BUILD_ROOT)
%{genfilelist} $RPM_BUILD_ROOT > %{name}-%{version}-filelist
echo "%doc COPYING" >> %{name}-%{version}-filelist

%post

%preun

%files -f %{name}-%{version}-filelist
%defattr(-,root,root)

%changelog
* Wed Feb 26 2014 Davide Principi <davide.principi@nethesis.it> - 1.1.1-1.ns6
- English version of TB plugin download page.

* Wed Feb 05 2014 Davide Principi <davide.principi@nethesis.it> - 1.1.0-1.ns6
- Rebuild for 6.5 beta3

* Tue Apr 30 2013 Davide Principi <davide.principi@nethesis.it> - 1.0.1-1.ns6
- Support Full automatic package install/upgrade/uninstall support #1870

* Tue Mar 19 2013 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.0-2.ns6
- Update URL tag Refs #1654
- Set minimum version requirements. #1653
 

* Fri Feb  8 2013 Davide Principi <davide.principi@nethesis.it> - 1.0.0-1.ns6
- Initial version




