/*
 * @Author: SLC
 * @Date: 2021-07-28 14:10:25
 * @LastEditors: SLC
 * @LastEditTime: 2021-07-28 14:11:23
 * @Description: file content
 */

BOOL fIsBrowser(std::string& paraStrBrowser)
{// 判断是否是浏览器

	if (std::string::npos != paraStrBrowser.find("Opera"))
	{
		paraStrBrowser = "Opera" ;
		return TRUE ;
	}
	else if (std::string::npos != paraStrBrowser.find("Internet Explorer"))
	{
		paraStrBrowser = "IE" ;
		return TRUE ;
	}
	else if (std::string::npos != paraStrBrowser.find("Firefox"))
	{
		paraStrBrowser = "Firefox" ;
		return TRUE ;
	}
	else if (std::string::npos != paraStrBrowser.find("Safari"))
	{
		paraStrBrowser = "Safari" ;
		return TRUE ;
	}
	else if (std::string::npos != paraStrBrowser.find("Tencent Traveler"))
	{
		paraStrBrowser = "腾讯TT" ;
		return TRUE ;
	}
	else if (std::string::npos != paraStrBrowser.find("搜狗高速浏览器"))
	{
		paraStrBrowser = "Sogo" ;
		return TRUE ;
	}
	else if (std::string::npos != paraStrBrowser.find("傲游"))
	{
		paraStrBrowser = "傲游" ;
		return TRUE ;
	}
	else if (std::string::npos != paraStrBrowser.find("360极速浏览器"))
	{
		paraStrBrowser = "360" ;
		return TRUE ;
	}
	else if (std::string::npos != paraStrBrowser.find("Chrome"))
	{
		paraStrBrowser = "Chrome" ;
		return TRUE ;
	}

	paraStrBrowser.erase() ;
	return FALSE ;
}

std::string strGetSubKeyValue(HKEY paraHKey, std::string& paraStrRegPath)
{
	HKEY hKey;
	long lRet = RegOpenKeyEx(paraHKey, paraStrRegPath.c_str(), 0, KEY_READ,&hKey);
	if (0 != lRet)
	{
		MessageBox(NULL, "从注册表中获取浏览器名称失败", "Error", MB_OK) ;
		exit(1) ;
	}

	int		dwIndex = 0 ;
	char	szValueName[MAX_PATH] = {0} ;
	DWORD	dwValueVameLen = MAX_PATH ;
	DWORD	dwType;
	union
	{
		BYTE data[1024];
		DWORD idata;
	}lpdata;
	DWORD dwDataLen = sizeof(lpdata);
	DWORD Type ;
	memset(&lpdata, 0, sizeof(lpdata));
	while(RegEnumValue(hKey, dwIndex, (LPTSTR)szValueName, &dwValueVameLen, 0, &Type, lpdata.data, &dwDataLen) != ERROR_NO_MORE_ITEMS)
	{
		if (!strcmp(szValueName, "DisplayName"))
		{// 相等看其Value是否是浏览器

			std::string strTemp((char*)lpdata.data) ;
			if (fIsBrowser(strTemp))
			{// 如果是的话，那么在函数内部做处理，然后返回
				return strTemp ;
			}
			else
			{
				return "" ;
			}
		}

		dwIndex++;
		dwValueVameLen  =   sizeof(szValueName);
		dwDataLen		=   sizeof(lpdata);
		memset(&lpdata, 0, sizeof(lpdata));
	}

	RegCloseKey(hKey) ;

	return "" ;
}

std::string& strGetS_1_5_21Path(std::string& paraStrPath)
{
	const int MAX_KEY_LENGTH = 255 ;
	const int MAX_VALUE_NAME = 16383 ;

	HKEY hKey ;
	if (0 != RegOpenKeyEx(HKEY_USERS, NULL, 0, KEY_READ, &hKey))
	{
		MessageBox(NULL, "从注册表获取浏览器记录失败", "Error", MB_OK) ;
		exit(1) ;
	}

	DWORD dwCntSubKeys = 0 ;
	if(RegQueryInfoKey(hKey, NULL, NULL, NULL, &dwCntSubKeys, NULL, NULL, NULL, NULL, NULL, NULL, NULL))
	{
		MessageBox(NULL, "从注册表获取浏览器记录失败", "Error", MB_OK) ;
		exit(1) ;
	}

	char			szKey[MAX_KEY_LENGTH] = {0} ;
	DWORD			dwKeyLength = MAX_KEY_LENGTH ;
	for (int i = 0; i < dwCntSubKeys; ++i)
	{
		memset(szKey, 0 ,MAX_KEY_LENGTH) ;
		dwKeyLength = MAX_KEY_LENGTH ;
		//DWORD n1 = RegEnumKeyEx(hKey, i, szKey, &dwKeyLength, NULL, NULL, NULL, NULL) ;
		if (RegEnumKeyEx(hKey, i, szKey, &dwKeyLength, NULL, NULL, NULL, NULL))
		{
			MessageBox(NULL, "从注册表获取浏览器记录失败", "Error", MB_OK) ;
			exit(1) ;
		}
		else
		{
			// 制造SubKey的Reg路径
			paraStrPath = szKey ;
			if (std::string::npos != paraStrPath.find("S-1-5-21-") && std::string::npos == paraStrPath.find("_Classes"))
			{
				paraStrPath += "\\Software\\Microsoft\\Windows\\CurrentVersion\\Uninstall\\" ;
				return paraStrPath ;
			}
		}
	}

	return paraStrPath ;
}

vector<pair<std::string, float>>& vecGetBrowser(HKEY hKeyRoot, HKEY& hKey, const std::string& strRegPath, vector<pair<std::string, float>>& retVecBrowser)
{
	//vector<pair<std::string, float>> retVecBrowser ;

	const int MAX_KEY_LENGTH = 255 ;
	const int MAX_VALUE_NAME = 16383 ;

	//std::string strRegPath("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Uninstall\\") ;

	// 读取注册表前期工作
	//HKEY hKey ;
	if (0 != RegOpenKeyEx(hKeyRoot, strRegPath.c_str(), 0, KEY_READ, &hKey))
	{
		MessageBox(NULL, "从注册表获取浏览器记录失败", "Error", MB_OK) ;
		exit(1) ;
	}

	// 开始遍历指定注册Key的Subkey
	DWORD dwCntSubKeys = 0 ;
	if(RegQueryInfoKey(hKey, NULL, NULL, NULL, &dwCntSubKeys, NULL, NULL, NULL, NULL, NULL, NULL, NULL))
	{
		MessageBox(NULL, "从注册表获取浏览器记录失败", "Error", MB_OK) ;
		exit(1) ;
	}

	if (!dwCntSubKeys)
	{
		return retVecBrowser ;
	}

	char			szKey[MAX_KEY_LENGTH] = {0} ;
	DWORD			dwKeyLength = MAX_KEY_LENGTH ;
	std::string		strTmp("") ;
	std::string		strRegPathTmp("") ;
	for (int i = 0; i < dwCntSubKeys; ++i)
	{
		memset(szKey, 0 ,MAX_KEY_LENGTH) ;
		dwKeyLength = MAX_KEY_LENGTH ;
		//DWORD n1 = RegEnumKeyEx(hKey, i, szKey, &dwKeyLength, NULL, NULL, NULL, NULL) ;
		if (RegEnumKeyEx(hKey, i, szKey, &dwKeyLength, NULL, NULL, NULL, NULL))
		{
			MessageBox(NULL, "从注册表获取浏览器记录失败", "Error", MB_OK) ;
			exit(1) ;
		}
		else
		{
			// 制造SubKey的Reg路径
			strRegPathTmp = strRegPath ;
			strRegPathTmp += szKey ;
			strRegPathTmp += "\\" ;

			// 获取SubKey的Value,指定要浏览器名称
			strTmp = strGetSubKeyValue(hKeyRoot, strRegPathTmp) ;
			if (!strTmp.empty())
			{
				pair<std::string, float> pairTemp ;
				pairTemp.first	= strTmp ;
				pairTemp.second = 0 ;

				// 首先判断该浏览器是否在，不在才加进去
				if (retVecBrowser.end() == find(retVecBrowser.begin(), retVecBrowser.end(), pairTemp))
				{
					retVecBrowser.push_back(pairTemp) ;
				}
			}
		}
	}

	return retVecBrowser ;
}

void vCaculateAverage(vector<pair<std::string, float>>& retVecBrowser)
{
	for (int i = 0; i < retVecBrowser.size(); ++i)
	{
		retVecBrowser[i].second = float(1) / retVecBrowser.size() ;
	}
}

// 获取浏览器种类名称
std::string strPath("SOFTWARE\\Microsoft\\Windows\\CurrentVersion\\Uninstall\\") ;
vecGetBrowser(HKEY_LOCAL_MACHINE, hKey, strPath, pBrowser) ;
std::string strS_1_5_21Path("") ;
vecGetBrowser(HKEY_USERS, hKey, strGetS_1_5_21Path(strS_1_5_21Path), pBrowser) ;
vCaculateAverage(pBrowser) ;
